<?php

namespace lujie\scheduling\monitor\models;

use lujie\executing\monitor\searches\ExecutableExecSearch;

/**
 * Class ScheduleTaskExecSearch
 * @package lujie\scheduling\monitor\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskExecSearch extends ExecutableExecSearch
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
