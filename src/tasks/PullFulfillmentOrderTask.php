<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullWarehouseStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentOrderTask extends CronTask
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int|string
     */
    public $shippedAtFrom = '-1 days';

    /**
     * @var int|string
     */
    public $shippedAtTo = 'now';

    /**
     * @var int
     */
    public $timeStep = 43200;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->shippedAtFrom = is_numeric($this->shippedAtFrom) ? $this->shippedAtFrom : strtotime($this->shippedAtFrom);
        $this->shippedAtTo = is_numeric($this->shippedAtTo) ? $this->shippedAtTo : strtotime($this->shippedAtTo);
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = FulfillmentAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            for ($timeFrom = $this->shippedAtFrom; $timeFrom <= $this->shippedAtTo; $timeFrom += $this->timeStep) {
                $timeTo = min($timeFrom + $this->timeStep, $this->shippedAtTo);
                $this->fulfillmentManager->pullShippedFulfillmentOrders($accountId, $timeFrom, $timeTo);
            }
            $this->fulfillmentManager->pullFulfillmentOrders($accountId);
        }
        return true;
    }
}
