<?php

namespace lujie\scheduling\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ScheduleTask]].
 *
 * @method ScheduleTaskQuery taskCode($taskCode)
 * @method ScheduleTaskQuery status($status)
 *
 * @method array|ScheduleTask[] all($db = null)
 * @method array|ScheduleTask|null one($db = null)
 *
 * @see ScheduleTask
 */
class ScheduleTaskQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'taskCode' => 'task_code',
                    'status' => 'status',
                ]
            ]
        ];
    }
}
