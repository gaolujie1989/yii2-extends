<?php

namespace lujie\user\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @method UserQuery id($id)
 * @method UserQuery orderById($sort = SORT_ASC)
 * @method UserQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method UserQuery userId($userId)
 * @method UserQuery username($username, bool $like = false)
 * @method UserQuery email($email, bool $like = false)
 * @method UserQuery authKey($authKey, bool $like = false)
 * @method UserQuery status($status)
 *
 * @method UserQuery createdAtBetween($from, $to = null)
 * @method UserQuery updatedAtBetween($from, $to = null)
 *
 * @method UserQuery active()
 * @method UserQuery inactive()
 *
 * @method UserQuery orderByUserId($sort = SORT_ASC)
 * @method UserQuery orderByCreatedAt($sort = SORT_ASC)
 * @method UserQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method UserQuery indexByUserId()
 * @method UserQuery indexByAuthKey()
 *
 * @method array getUserIds()
 * @method array getAuthKeys()
 *
 * @method array|User[] all($db = null)
 * @method array|User|null one($db = null)
 * @method array|User[] each($batchSize = 100, $db = null)
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
                    'userId' => 'user_id',
                    'username' => ['username'],
                    'email' => ['email'],
                    'authKey' => 'auth_key',
                    'status' => 'status',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inactive' => ['status' => StatusConst::STATUS_ACTIVE],
                ],
                'querySorts' => [
                    'orderByUserId' => 'user_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByUserId' => 'user_id',
                    'indexByAuthKey' => 'auth_key',
                ],
                'queryReturns' => [
                    'getUserIds' => ['user_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getAuthKeys' => ['auth_key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getUserNames(): array
    {
        $users = $this->select(['user_id', 'username'])->asArray()->all();
        return ArrayHelper::map($users, 'user_id', 'username');
    }
}
