<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\user\accessToken;

/**
 * Interface AccessTokenInterface
 * @package lujie\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface AccessTokenManagerInterface
{
    /**
     * @param $token
     * @param string|null $tokenType
     * @return int|null
     * @inheritdoc
     */
    public function getUserId($token, ?string $tokenType = null): ?int;

    /**
     * @param int $userId
     * @param string|null $tokenType
     * @param int $duration
     * @param int $length
     * @return string
     * @inheritdoc
     */
    public function createAccessToken(int $userId, ?string $tokenType = null, int $duration = 86400, int $length = 64): string;
}
