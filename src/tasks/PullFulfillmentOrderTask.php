<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
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
class PullFulfillmentOrderTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

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
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->shippedAtFrom = is_numeric($this->shippedAtFrom) ? $this->shippedAtFrom : strtotime($this->shippedAtFrom);
        $this->shippedAtTo = is_numeric($this->shippedAtTo) ? $this->shippedAtTo : strtotime($this->shippedAtTo);
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = FulfillmentAccount::find()->active()->column();
        $total = ceil(($this->shippedAtTo - $this->shippedAtFrom) / $this->timeStep + 1) * count($accountIds);
        $progress = $this->getProgress($total);
        foreach ($accountIds as $accountId) {
            for ($timeFrom = $this->shippedAtFrom; $timeFrom <= $this->shippedAtTo; $timeFrom += $this->timeStep) {
                $progress->message = date('Y-m-d H:i', $timeFrom);
                $timeTo = min($timeFrom + $this->timeStep, $this->shippedAtTo);
                $this->fulfillmentManager->pullShippedFulfillmentOrders($accountId, $timeFrom, $timeTo);
                $progress->done++;
                yield true;
            }
            $this->fulfillmentManager->pullFulfillmentOrders($accountId);
            $progress->done++;
            yield true;
        }
    }
}
