<?php

namespace lujie\scheduling\monitor\models;

use lujie\executing\monitor\models\ExecutableExec;

/**
 * Class ScheduleTaskExec
 * @package lujie\executing\monitor\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskExec extends ExecutableExec
{
    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%schedule_task_exec}}';
    }
}
