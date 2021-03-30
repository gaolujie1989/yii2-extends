<?php

namespace lujie\project\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[TaskGroup]].
 *
 * @method TaskGroupQuery id($id)
 * @method TaskGroupQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method TaskGroupQuery taskGroupId($taskGroupId)
 * @method TaskGroupQuery projectId($projectId)
 *
 * @method array|TaskGroup[] all($db = null)
 * @method array|TaskGroup|null one($db = null)
 * @method array|TaskGroup[] each($batchSize = 100, $db = null)
 *
 * @see TaskGroup
 */
class TaskGroupQuery extends \yii\db\ActiveQuery
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
                    'taskGroupId' => 'task_group_id',
                    'projectId' => 'project_id',
                ]
            ]
        ];
    }
}
