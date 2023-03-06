<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tasks;

use lujie\queuing\monitor\behaviors\BaseJobMonitorBehavior;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class CleanQueueMonitorTask
 * @package lujie\scheduling\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CleanQueueMonitorTask extends BaseObject
{
    /**
     * @var Queue
     */
    public $queue;

    /**
     * @var string
     */
    public $jobMonitorBehaviors = 'jobMonitor';

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->queue = Instance::ensure($this->queue, Queue::class);
        $behavior = $this->queue->getBehavior($this->jobMonitorBehaviors);
        if ($behavior && $behavior instanceof BaseJobMonitorBehavior) {
            $behavior->cleanJobAndExec(true);
        }
    }
}
