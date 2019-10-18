<?php

namespace lujie\project\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Task]].
 *
 * @method TaskGroupQuery projectId($projectId)
 *
 * @method TaskQuery normal()
 * @method TaskQuery archived()
 * @method TaskQuery deleted()
 *
 * @method Task[]|array all($db = null)
 * @method Task|array|null one($db = null)
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
                    'projectId' => ['project_id'],
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
