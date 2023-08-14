<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use lujie\executing\Executor;
use yii\di\Instance;

/**
 * Trait ScheduleSubTaskTrait
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ScheduleSubTaskTrait
{
    /**
     * @var Executor
     */
    public $subTaskExecutor = 'scheduler';

    /**
     * @return Executor|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getSubTaskExecutor(): Executor
    {
        return Instance::ensure($this->subTaskExecutor, Executor::class);
    }

    /**
     * @param ScheduleTaskInterface $subTask
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function handleSubTask(ScheduleTaskInterface $subTask): void
    {
        $this->getSubTaskExecutor()->handle($subTask);
    }
}
