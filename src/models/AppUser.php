<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\models;


/**
 * Class AppUser
 * @package lujie\user\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AppUser extends User
{
    public static function findIdentityByAccessToken($token, $type = null): ?User
    {
        $key = ($type ?? '') . $token;
        $userId = static::getCache()->get(static::getUserIdCacheKey($key))
            ?: static::getCache()->get(static::getUserIdCacheKey($token));
        if ($userId) {
            return static::findIdentity($userId);
        }
        return null;
    }
}
