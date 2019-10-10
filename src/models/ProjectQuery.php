<?php

namespace lujie\project\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Project]].
 *
 * @method ProjectQuery ownerId($ownerId)
 *
 * @method ProjectQuery notSystem()
 * @method ProjectQuery normal()
 * @method ProjectQuery archived()
 * @method ProjectQuery deleted()
 *
 * @method Project[]|array all($db = null)
 * @method Project|array|null one($db = null)
 *
 * @see Project
 */
class ProjectQuery extends \yii\db\ActiveQuery
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
                    'ownerId' => ['owner_id'],
                ],
                'queryConditions' => [
                    'notSystem' => ['!=', 'visibility', Project::VISIBILITY_SYSTEM],
                    'normal' => ['archived_at' => 0, 'deleted_at' => 0],
                    'archived' => ['AND', ['>', 'archived_at', 0], ['deleted_at' => 0]],
                    'deleted' => ['>', 'deleted_at', 0],
                ]
            ]
        ];
    }
}
