<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\sources\QuerySource;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\extend\helpers\QueryHelper;
use lujie\fulfillment\models\FulfillmentDailyStock;
use lujie\fulfillment\models\FulfillmentDailyStockMovement;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * Class DailyStockGenerator
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DailyStockGenerator extends BaseObject
{
    /**
     * @var string
     */
    public $dailyTimeStartFormat = 'Y-m-d 00:00:00';

    /**
     * @var string
     */
    public $dailyTimeEndFormat = 'Y-m-d 23:59:59';

    /**
     * @var string
     */
    public $stockDateFormat = 'Y-m-d';

    /**
     * @param string|int $dateFrom
     * @param string|int $dateTo
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function generateDailyStockMovements($dateFrom, $dateTo): bool
    {
        $movementAtFrom = strtotime(date($this->dailyTimeStartFormat, is_numeric($dateFrom) ? $dateFrom : strtotime($dateFrom)));
        $movementAtTo = strtotime(date($this->dailyTimeEndFormat, is_numeric($dateTo) ? $dateTo : strtotime($dateTo)));

        $commonFields = [
            'fulfillment_account_id',
            'item_id',
            'warehouse_id',
            'external_item_key',
            'external_warehouse_key',
            'reason'
        ];
        $query = FulfillmentWarehouseStockMovement::find()
            ->addSelect($commonFields)
            ->addSelect([
                "SUM(moved_qty) AS moved_qty",
                "COUNT(moved_qty) AS moved_count",
                "DATE_FORMAT(FROM_UNIXTIME(external_created_at), '%Y-%m-%d') AS moved_date"
            ])
            ->addGroupBy($commonFields)
            ->addGroupBy(['moved_date'])
            ->andWhere(['BETWEEN', 'external_created_at', $movementAtFrom, $movementAtTo])
            ->asArray();

        $dataExchanger = new DataExchanger([
            'source' => [
                'class' => QuerySource::class,
                'query' => $query,
            ],
            'pipeline' => [
                'class' => DbPipeline::class,
                'modelClass' => FulfillmentDailyStockMovement::class,
                'indexKeys' => array_merge($commonFields, ['moved_date'])
            ]
        ]);
        if ($dataExchanger->execute()) {
            Yii::info('Generate Daily Stock Movements Success,'
                . ' AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts()), __METHOD__);
            return true;
        }
        Yii::error('Generate Daily Stock Movements Failed,'
            . ' AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts())
            . ' Errors: ' . Json::encode($dataExchanger->getErrors()), __METHOD__);
        return false;
    }

    /**
     * @param string|int $dateFrom
     * @param string|int $dateTo
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function generateDailyStocks($dateFrom, $dateTo): bool
    {
        $stockDateFrom = date($this->stockDateFormat, is_numeric($dateFrom) ? $dateFrom : strtotime($dateFrom));
        $stockDateTo = date($this->stockDateFormat, is_numeric($dateTo) ? $dateTo : strtotime($dateTo));

        $commonFields = [
            'fulfillment_account_id',
            'item_id',
            'warehouse_id',
            'external_item_key',
            'external_warehouse_key',
        ];
        $dailyStockFields = array_map(static function ($field) {
            return "ds.{$field}";
        }, $commonFields);
        $dailyMovementFields = array_map(static function ($field) {
            return "dsm.{$field}";
        }, $commonFields);
        $joinCondition = array_map(static function ($field) {
            return "ds.{$field} = dsm.{$field}";
        }, $commonFields);
        $joinCondition = implode(' AND ', $joinCondition);

        $dataExchanger = new DataExchanger([
            'transformer' => [
                'class' => ChainedTransformer::class,
                'transformers' => [
                    static function ($data) {
                        return array_map(static function($values) {
                            $values['stock_qty'] = ($values['prev_stock_qty'] ?? 0) + ($values['moved_qty'] ?: 0);
                            unset($values['prev_stock_qty'], $values['moved_qty']);
                            return $values;
                        }, $data);
                    }
                ],
            ],
            'pipeline' => [
                'class' => DbPipeline::class,
                'modelClass' => FulfillmentDailyStock::class,
                'indexKeys' => array_merge($commonFields, ['stock_date'])
            ]
        ]);

        for ($stockDate = $stockDateFrom; $stockDate <= $stockDateTo; $stockDate = date($this->stockDateFormat, strtotime($stockDate) + 86400)) {
            $prevStockDate = date($this->stockDateFormat, strtotime($stockDate) - 86400);
            $prev2StockDate = date($this->stockDateFormat, strtotime($stockDate) - 86400 * 2);

            //Generate Daily Stock if Prev Daily Stock Exists.
            $dailyStockQuery = FulfillmentDailyStock::find()->alias('ds')
                ->leftJoin(['dsm' => FulfillmentDailyStockMovement::tableName()], $joinCondition . " AND dsm.moved_date = '{$stockDate}'")
                ->andWhere(['ds.stock_date' => $prevStockDate])
                ->addSelect($dailyStockFields)
                ->addSelect(['SUM(moved_qty) as moved_qty'])
                ->addSelect(['ds.stock_qty as prev_stock_qty', new Expression("'{$stockDate}' as stock_date")])
                ->addGroupBy($dailyStockFields)
                ->addGroupBy(['prev_stock_qty'])
                ->asArray();

            $dataExchanger->source = new QuerySource([
                'query' => $dailyStockQuery,
            ]);
            if (!$dataExchanger->execute()) {
                Yii::error('Generate Daily Stocks Failed,'
                    . ' AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts())
                    . ' Errors: ' . Json::encode($dataExchanger->getErrors()), __METHOD__);
                return false;
            }

            //Generate Daily Stock if Prev Daily Stock Not Exists. Item Stock is first movement
            $dailyMovementQuery = FulfillmentDailyStockMovement::find()->alias('dsm')
                ->leftJoin(['ds' => FulfillmentDailyStock::tableName()], $joinCondition . " AND ds.stock_date <= '{$prevStockDate}'")
                ->andWhere(['dsm.moved_date' => $stockDate])
                ->andWhere('ds.stock_date IS NULL')
                ->addSelect($dailyMovementFields)
                ->addSelect(['SUM(moved_qty) as moved_qty'])
                ->addSelect([new Expression("'{$stockDate}' as stock_date")])
                ->addGroupBy($dailyMovementFields)
                ->asArray();

            $dataExchanger->source = new QuerySource([
                'query' => $dailyMovementQuery,
            ]);
            if (!$dataExchanger->execute()) {
                Yii::error('Generate Daily Stocks Failed,'
                    . ' AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts())
                    . ' Errors: ' . Json::encode($dataExchanger->getErrors()), __METHOD__);
                return false;
            }

            //IF Prev 2 days stock exists, but prev day stock not exist. Error
            $missingPrevDailyStockQuery = FulfillmentDailyStockMovement::find()->alias('dsm')
                ->leftJoin(['ds' => FulfillmentDailyStock::tableName()], $joinCondition . " AND ds.stock_date = {$prevStockDate}")
                ->leftJoin(['ds2' => FulfillmentDailyStock::tableName()], $joinCondition . " AND ds2.stock_date <= {$prev2StockDate}")
                ->andWhere(['dsm.moved_date' => $stockDate])
                ->andWhere('ds.stock_date IS NULL')
                ->andWhere('ds2.stock_date IS NOT NULL')
                ->addSelect($dailyMovementFields)
                ->distinct()
                ->asArray();
            $count = $missingPrevDailyStockQuery->count();
            if ($count) {
                $missingItems = $missingPrevDailyStockQuery->all();
                Yii::error("Missing Prev Daily Stocks {$count} of date {$stockDate}, Items: " . Json::encode($missingItems), __METHOD__);
                return false;
            }
        }
        Yii::info('Generate Daily Stocks Success, AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts()), __METHOD__);
        return true;
    }
}