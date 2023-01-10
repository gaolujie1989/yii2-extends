<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\extend\queue\RateLimitDelayJobInterface;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentOrder;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\queue\JobInterface;

/**
 * Class FulfillmentOrderActionJob
 * @package lujie\fulfillment\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseFulfillmentOrderJob extends BaseObject implements JobInterface, RateLimitDelayJobInterface
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
     * @var int
     */
    public $rateLimitDelay = 2;

    /**
     * @return FulfillmentOrder
     * @inheritdoc
     */
    protected function getFulfillmentOrder(): FulfillmentOrder
    {
        /** @var ?FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = FulfillmentOrder::findOne($this->fulfillmentOrderId);
        if ($fulfillmentOrder === null) {
            throw new InvalidArgumentException("Invalid fulfillmentOrderId {$this->fulfillmentOrderId}");
        }
        return $fulfillmentOrder;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        $fulfillmentOrder = $this->getFulfillmentOrder();
        return 'FulfillmentOrderAccount:' . $fulfillmentOrder->fulfillment_account_id;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int
    {
        return $this->rateLimitDelay;
    }
}
