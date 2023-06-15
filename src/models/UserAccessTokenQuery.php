<?php

namespace lujie\user\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[UserAccessToken]].
 *
 * @method UserAccessTokenQuery id($id)
 * @method UserAccessTokenQuery orderById($sort = SORT_ASC)
 * @method UserAccessTokenQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method UserAccessTokenQuery userAccessTokenId($userAccessTokenId)
 * @method UserAccessTokenQuery userId($userId)
 * @method UserAccessTokenQuery accessToken($accessToken)
 * @method UserAccessTokenQuery tokenType($tokenType)
 *
 * @method UserAccessTokenQuery expiredAtBetween($from, $to = null)
 * @method UserAccessTokenQuery lastAccessedAtBetween($from, $to = null)
 * @method UserAccessTokenQuery createdAtBetween($from, $to = null)
 * @method UserAccessTokenQuery updatedAtBetween($from, $to = null)
 *
 * @method UserAccessTokenQuery orderByUserAccessTokenId($sort = SORT_ASC)
 * @method UserAccessTokenQuery orderByUserId($sort = SORT_ASC)
 * @method UserAccessTokenQuery orderByExpiredAt($sort = SORT_ASC)
 * @method UserAccessTokenQuery orderByLastAccessedAt($sort = SORT_ASC)
 * @method UserAccessTokenQuery orderByCreatedAt($sort = SORT_ASC)
 * @method UserAccessTokenQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method UserAccessTokenQuery indexByUserAccessTokenId()
 * @method UserAccessTokenQuery indexByUserId()
 *
 * @method array getUserAccessTokenIds()
 * @method array getUserIds()
 *
 * @method array|UserAccessToken[] all($db = null)
 * @method array|UserAccessToken|null one($db = null)
 * @method array|UserAccessToken[] each($batchSize = 100, $db = null)
 *
 * @see UserAccessToken
 */
class UserAccessTokenQuery extends \yii\db\ActiveQuery
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
                    'userAccessTokenId' => 'user_access_token_id',
                    'userId' => 'user_id',
                    'accessToken' => 'access_token',
                    'tokenType' => 'token_type',
                    'expiredAtBetween' => ['expired_at' => 'BETWEEN'],
                    'lastAccessedAtBetween' => ['last_accessed_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByUserAccessTokenId' => 'user_access_token_id',
                    'orderByUserId' => 'user_id',
                    'orderByExpiredAt' => 'expired_at',
                    'orderByLastAccessedAt' => 'last_accessed_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByUserAccessTokenId' => 'user_access_token_id',
                    'indexByUserId' => 'user_id',
                ],
                'queryReturns' => [
                    'getUserAccessTokenIds' => ['user_access_token_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getUserIds' => ['user_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
