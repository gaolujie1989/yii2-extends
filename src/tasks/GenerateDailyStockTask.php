<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;


use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;

/**
 * Class GenerateDailyStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateDailyStockTask extends CronTask
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
        $stockDateFromTime = strtotime(date($this->dailyTimeFormat, strtotime($this->stockDateFrom)));
        $stockDateToTime = strtotime(date($this->dailyTimeFormat, strtotime($this->stockDateTo)));

        $accountIds = FulfillmentAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            $warehouseIds = FulfillmentWarehouse::find()
                ->fulfillmentAccountId($accountId)
                ->getWarehouseIds();
            $itemQuery = FulfillmentItem::find()
                ->fulfillmentAccountId($accountId)
                ->itemPushed();

        }
    }
}