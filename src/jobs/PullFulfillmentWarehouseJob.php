<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class FulfillmentExecutable
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentWarehouseJob extends BaseObject implements JobInterface
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int
     */
    public $fulfillmentAccountId;

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute($queue)
    {
        /** @var FulfillmentAccount $fulfillmenAccount */
        $fulfillmentAccount = FulfillmentAccount::findOne($this->fulfillmentAccountId);
        if ($fulfillmentAccount === null) {
            throw new InvalidArgumentException('Invalid fulfillmentAccountId');
        }

        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pullFulfillmentWarehouses($this->fulfillmentAccountId, $this->condition);
    }
}
