<?php

namespace lujie\project\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Task]].
 *
 * @method TaskQuery id($id)
 * @method TaskQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method TaskQuery taskId($taskId)
 * @method TaskQuery projectId($projectId)
 * @method TaskQuery taskGroupId($taskGroupId)
 * @method TaskQuery parentTaskId($parentTaskId)
 * @method TaskQuery status($status)
 * @method TaskQuery ownerId($ownerId)
 * @method TaskQuery executorId($executorId)
 *
 * @method TaskQuery normal()
 * @method TaskQuery archived()
 * @method TaskQuery deleted()
 *
 * @method array|Task[] all($db = null)
 * @method array|Task|null one($db = null)
 * @method array|Task[] each($batchSize = 100, $db = null)
 *
 * @see Task
 */
class TaskQuery extends \yii\db\ActiveQuery
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
                    'taskId' => 'task_id',
                    'projectId' => 'project_id',
                    'taskGroupId' => 'task_group_id',
                    'parentTaskId' => 'parent_task_id',
                    'status' => 'status',
                    'ownerId' => 'owner_id',
                    'executorId' => 'executor_id',
                ],
                'queryConditions' => [
                    'normal' => ['archived_at' => 0, 'deleted_at' => 0],
                    'archived' => ['AND', ['>', 'archived_at', 0], ['deleted_at' => 0]],
                    'deleted' => ['>', 'deleted_at', 0],
                ]
            ]
        ];
    }

    /**
     * @param bool $isSubTask
     * @return TaskQuery
     * @inheritdoc
     */
    public function isSubTask($isSubTask = true): TaskQuery
    {
        if ($isSubTask) {
            $this->andWhere(['>', 'parent_task_id', 0]);
        } else {
            $this->andWhere(['=', 'parent_task_id', 0]);
        }
        return $this;
    }
}
