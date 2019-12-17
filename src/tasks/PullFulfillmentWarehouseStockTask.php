<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;


use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\scheduling\CronTask;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class PullWarehouseStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentWarehouseStockTask extends CronTask
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = FulfillmentAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            $this->fulfillmentManager->pullFulfillmentWarehouseStocks($accountId);
        }
    }
}
