<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\recording\models\DataSource;
use lujie\scheduling\CronTask;
use lujie\scheduling\Scheduler;
use lujie\scheduling\ScheduleTaskInterface;
use yii\di\Instance;

/**
 * Class RecordingScheduler
 * @package kiwi\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingScheduler extends Scheduler
{
    /**
     * @var string
     */
    public $mutexNamePrefix = 'RecordingScheduler:';

    /**
     * @param int|string $taskId
     * @param DataSource $taskConfig
     * @return ScheduleTaskInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function createTask($taskId, $taskConfig): ScheduleTaskInterface
    {
        $dataSource = $taskConfig;
        $task = new CronTask();
        $task->id = $taskId;
        $task->executable = '';
        if (isset($dataSource->options['expression'])) {
            $task->expression = $dataSource->options['expression'];
        }

        $task->shouldQueued = true;
        $task->ttr = 3600;

        $task->shouldLocked = true;
        return $task;
    }
}
