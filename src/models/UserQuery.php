<?php

namespace lujie\user\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @method UserQuery id($id)
 * @method UserQuery username($username)
 * @method UserQuery email($email)
 *
 * @method UserQuery active()
 * @method UserQuery inactive()
 *
 * @method array|User[] all($db = null)
 * @method array|User|null one($db = null)
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
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
                    'username' => ['username'],
                    'email' => ['email'],
                ],
                'queryConditions' => [
                    'active' => ['status' => User::STATUS_ACTIVE],
                    'inactive' => ['status' => User::STATUS_ACTIVE],
                ]
            ]
        ];
    }
}
