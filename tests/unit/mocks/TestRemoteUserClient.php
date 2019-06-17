<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user\tests\unit\mocks;


use lujie\remote\user\RemoteUserClient;

/**
 * Class TestRemoteUserClient
 * @package lujie\remote\user\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestRemoteUserClient extends RemoteUserClient
{
    /**
     * @param $token
     * @param null $type
     * @return array|null
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getUserByAccessToken(string $token, string $type = null): ?array
    {
        if ($token === 'token_123') {
            return [
                'id' => 123,
                'username' => 'username_123',
                'authKey' => 'auth_key_123'
            ];
        }
        return null;
    }
}
