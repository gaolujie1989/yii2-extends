<?php

namespace lujie\user\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[UserApp]].
 *
 * @method UserAppQuery id($id)
 * @method UserAppQuery userId($userId)
 * @method UserAppQuery name($name)
 * @method UserAppQuery key($key)
 * @method UserAppQuery secret($secret)
 *
 * @method UserAppQuery active()
 * @method UserAppQuery inactive()
 *
 * @method array|UserApp[] all($db = null)
 * @method array|UserApp|null one($db = null)
 *
 * @see UserApp
 */
class UserAppQuery extends \yii\db\ActiveQuery
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
                    'userId' => 'user_id',
                    'name' => 'name',
                    'key' => 'key',
                    'secret' => 'secret',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inactive' => ['status' => StatusConst::STATUS_ACTIVE],
                ]
            ]
        ];
    }
}
