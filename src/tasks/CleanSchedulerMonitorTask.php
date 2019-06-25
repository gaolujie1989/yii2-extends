<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tasks;

use lujie\executing\monitor\behaviors\BaseMonitorBehavior;
use lujie\scheduling\Scheduler;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class CleanSchedulerMonitorTask
 * @package lujie\scheduling\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CleanSchedulerMonitorTask extends BaseObject
{
    /**
     * @var Scheduler
     */
    public $scheduler = 'scheduler';

    /**
     * @var string
     */
    public $monitorBehavior = 'monitor';

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
        $behavior = $this->scheduler->getBehavior($this->monitorBehavior);
        if ($behavior && $behavior instanceof BaseMonitorBehavior) {
            $behavior->cleanExec(true);
        }
    }
}
