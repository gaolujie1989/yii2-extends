<?php

namespace lujie\common\oauth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[AuthToken]].
 *
 * @method AuthTokenQuery id($id)
 * @method AuthTokenQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method AuthTokenQuery authTokenId($authTokenId)
 * @method AuthTokenQuery userId($userId)
 * @method AuthTokenQuery authService($source)
 * @method AuthTokenQuery authUserId($authUserId)
 * @method AuthTokenQuery authUsername($sourceName)
 *
 * @method array|AuthToken[] all($db = null)
 * @method array|AuthToken|null one($db = null)
 * @method array|AuthToken[] each($batchSize = 100, $db = null)
 *
 * @see AuthToken
 */
class AuthTokenQuery extends \yii\db\ActiveQuery
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
                    'authTokenId' => 'auth_token_id',
                    'userId' => 'user_id',
                    'authService' => 'auth_service',
                    'authUserId' => 'auth_user_id',
                    'authUsername' => 'auth_username',
                ]
            ]
        ];
    }

}
