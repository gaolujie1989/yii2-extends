<?php

namespace lujie\scheduling\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[ScheduleTask]].
 *
 * @method ScheduleTaskQuery id($id)
 * @method ScheduleTaskQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ScheduleTaskQuery scheduleTaskId($scheduleTaskId)
 * @method ScheduleTaskQuery taskCode($taskCode)
 * @method ScheduleTaskQuery status($status)
 *
 * @method ScheduleTaskQuery active()
 *
 * @method array|ScheduleTask[] all($db = null)
 * @method array|ScheduleTask|null one($db = null)
 * @method array|ScheduleTask[] each($batchSize = 100, $db = null)
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
                    'scheduleTaskId' => 'schedule_task_id',
                    'taskCode' => 'task_code',
                    'status' => 'status',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                ]
            ]
        ];
    }
}
