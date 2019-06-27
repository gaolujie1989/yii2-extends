<?php

include_once Yii::getAlias('@lujie/executing/monitor/migrations/m190624_113602_executable_exec');

class m190624_113602_schedule_task_exec extends m190624_113602_executable_exec
{
    public $tableName = '{{%schedule_task_exec}}';
}
