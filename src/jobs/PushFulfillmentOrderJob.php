<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
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
class PushFulfillmentOrderJob extends BaseObject implements JobInterface
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int
     */
    public $fulfillmentOrderId;

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute($queue)
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = FulfillmentOrder::findOne($this->fulfillmentOrderId);
        if ($fulfillmentOrder === null) {
            throw new InvalidArgumentException('Invalid fulfillmentItemId');
        }

        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pushFulfillmentOrder($fulfillmentOrder);
    }
}
