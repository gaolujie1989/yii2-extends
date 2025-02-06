<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\user\accessToken;

use lujie\user\models\UserAccessToken;
use Yii;
use yii\base\BaseObject;

/**
 * Class DbAccessToken
 * @package lujie\user\accessToken
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbAccessTokenManager extends BaseObject implements AccessTokenManagerInterface
{
    /**
     * @param $token
     * @param string|null $tokenType
     * @return int|null
     * @inheritdoc
     */
    public function getUserId($token, ?string $tokenType = null): ?int
    {
        $query = UserAccessToken::find()
            ->accessToken($token)
            ->expiredAtBetween(time())
            ->cache();
        if ($tokenType) {
            $query->tokenType($tokenType);
        }
        $userAccessToken = $query->one();
        return $userAccessToken?->user_id;
    }

    /**
     * @param int $userId
     * @param string|null $tokenType
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function createAccessToken(int $userId, ?string $tokenType = null, int $duration = 86400, int $length = 64): string
    {
        $token = Yii::$app->security->generateRandomString($length);
        $userAccessToken = new UserAccessToken();
        $userAccessToken->user_id = $userId;
        $userAccessToken->access_token = $token;
        $userAccessToken->token_type = $tokenType ?? '';
        $userAccessToken->expired_at = time() + $duration;
        $userAccessToken->last_accessed_at = 0;
        $userAccessToken->save(false);
        return $token;
    }
}
