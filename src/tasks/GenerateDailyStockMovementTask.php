<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;


use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;

/**
 * Class GenerateDailyStockMovementTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateDailyStockMovementTask extends CronTask
{
    /**
     * @var string
     */
    public $stockDateFrom = '-2 days';

    /**
     * @var string
     */
    public $stockDateTo = '-1 days';

    /**
     * @var string
     */
    public $dailyTimeFormat = 'Y-m-d 23:59:59';

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        FulfillmentWarehouseStockMovement::find()
            ->addSelect([
                'fulfillment_account_id',
                'item_id',
                'warehouse_id',
                'external_item_key',
                'external_warehouse_key',
                'reason'
            ])
            ->addSelect([
                "SUM(moved_qty) AS moved_qty",
                "COUNT(moved_qty) AS moved_count",
                "DATE_FORMAT(FROM_UNIXTIME(external_created_at), '%Y-%m-%d') AS moved_date"
            ])
            ->andWhere(['external_created_at']);
    }
}