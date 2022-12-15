<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\sources\QuerySource;
use lujie\data\exchange\transformers\ChainedTransformer;
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
class FulfillmentDailyStockGenerator extends BaseObject
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
            'external_item_key',
            'external_warehouse_key',
            'movement_type',
        ];
        $query = FulfillmentWarehouseStockMovement::find()
            ->addSelect($commonFields)
            ->addSelect([
                "SUM(movement_qty) AS movement_qty",
                "COUNT(movement_qty) AS movement_count",
                "DATE_FORMAT(FROM_UNIXTIME(external_created_at), '%Y-%m-%d') AS movement_date"
            ])
            ->addGroupBy($commonFields)
            ->addGroupBy(['movement_date'])
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
                'indexKeys' => array_merge($commonFields, ['movement_date'])
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
        $commonFields = [
            'fulfillment_account_id',
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
                        $data = array_filter($data, static function($values) {
                            return (int)($values['prev_stock_qty'] ?? 0) !== 0 || (int)$values['movement_count'] !== 0;
                        });
                        return array_map(static function ($values) {
                            $values['stock_qty'] = ($values['prev_stock_qty'] ?? 0) + ($values['movement_qty'] ?: 0);
                            unset($values['prev_stock_qty'], $values['movement_qty']);
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

        $dateAtFrom = is_numeric($dateFrom) ? $dateFrom : strtotime($dateFrom);
        $dateAtTo = is_numeric($dateTo) ? $dateTo : strtotime($dateTo);

        for ($stockDateAt = $dateAtFrom; $stockDateAt <= $dateAtTo; $stockDateAt += 86400) {
            $stockDate = date($this->stockDateFormat, $stockDateAt);
            $prevStockDate = date($this->stockDateFormat, $stockDateAt - 86400);

            //Generate Daily Stock if Prev Daily Stock Exists.
            //Query from daily stock because movement maybe not exist at that day
            $dailyStockQuery = FulfillmentDailyStock::find()->alias('ds')
                ->leftJoin(['dsm' => FulfillmentDailyStockMovement::tableName()], $joinCondition . " AND dsm.movement_date = '{$stockDate}'")
                ->andWhere(['ds.stock_date' => $prevStockDate])
                ->addSelect($dailyStockFields)
                ->addSelect(['SUM(movement_qty) as movement_qty', 'SUM(movement_count) AS movement_count'])
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

            //Generate Daily Stock if Prev Daily Stock Not Exists. Item Stock is first movement or Prev Daily Stock is 0
            $dailyMovementQuery = FulfillmentDailyStockMovement::find()->alias('dsm')
                ->leftJoin(['ds' => FulfillmentDailyStock::tableName()], $joinCondition . " AND ds.stock_date <= '{$prevStockDate}'")
                ->andWhere(['dsm.movement_date' => $stockDate])
                ->andWhere('ds.stock_date IS NULL')
                ->addSelect($dailyMovementFields)
                ->addSelect(['SUM(movement_qty) as movement_qty', 'SUM(movement_count) AS movement_count'])
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
        }
        Yii::info('Generate Daily Stocks Success, AffectedRowCounts: ' . Json::encode($dataExchanger->getAffectedRowCounts()), __METHOD__);
        return true;
    }
}
