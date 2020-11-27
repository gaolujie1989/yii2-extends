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
     * @var ?string
     */
    public $shippedAtFrom;

    /**
     * @var ?string
     */
    public $shippedAtTo;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->shippedAtFrom = $this->shippedAtFrom ? strtotime($this->shippedAtFrom) : $this->shippedAtFrom;
        $this->shippedAtTo = $this->shippedAtTo ? strtotime($this->shippedAtTo) : $this->shippedAtTo;
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = FulfillmentAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            $this->fulfillmentManager->pullShippedFulfillmentOrders($accountId, $this->shippedAtFrom, $this->shippedAtTo);
            $this->fulfillmentManager->pullFulfillmentOrders($accountId);
        }
        return true;
    }
}
