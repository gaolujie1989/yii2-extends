<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;


use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use lujie\fulfillment\FulfillmentManager;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class PullWarehouseStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentOrderTask extends BaseObject implements ExecutableInterface
{
    use ExecutableTrait;

    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    public $fulfillmentAccountId;

    /**
     * @return int|string
     * @inheritdoc
     */
    public function getId()
    {
        return $this->fulfillmentAccountId;
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pullFulfillmentOrders($this->fulfillmentAccountId);
    }
}
