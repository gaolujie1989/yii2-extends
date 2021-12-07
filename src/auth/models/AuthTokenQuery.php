<?php

namespace lujie\common\oauth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OauthToken]].
 *
 * @method OauthTokenQuery id($id)
 * @method OauthTokenQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method OauthTokenQuery oauthTokenId($oauthTokenId)
 * @method OauthTokenQuery userId($userId)
 * @method OauthTokenQuery sourceId($sourceId)
 *
 * @method array|OauthToken[] all($db = null)
 * @method array|OauthToken|null one($db = null)
 * @method array|OauthToken[] each($batchSize = 100, $db = null)
 *
 * @see OauthToken
 */
class OauthTokenQuery extends \yii\db\ActiveQuery
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
                    'oauthTokenId' => 'oauth_token_id',
                    'userId' => 'user_id',
                    'sourceId' => 'source_id',
                ]
            ]
        ];
    }

}
