<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\extend\queue\RetryableJobTrait;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentOrder;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

/**
 * Class FulfillmentOrderActionJob
 * @package lujie\fulfillment\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseFulfillmentOrderJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    use RetryableJobTrait;

    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int
     */
    public $fulfillmentOrderId;

    /**
     * @return FulfillmentOrder
     * @inheritdoc
     */
    protected function getFulfillmentOrder(): FulfillmentOrder
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = FulfillmentOrder::findOne($this->fulfillmentOrderId);
        if ($fulfillmentOrder === null) {
            throw new InvalidArgumentException('Invalid fulfillmentItemId');
        }
        return $fulfillmentOrder;
    }
}
