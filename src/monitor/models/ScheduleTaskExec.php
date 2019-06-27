<?php

namespace lujie\executing\monitor\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

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
