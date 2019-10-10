<?php

namespace lujie\project\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[TaskGroup]].
 *
 * @method TaskGroupQuery projectId($projectId)
 *
 * @method TaskGroup[]|array all($db = null)
 * @method TaskGroup|array|null one($db = null)
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
                    'projectId' => ['project_id'],
                ],
            ]
        ];
    }
}
