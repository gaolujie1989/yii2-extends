<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user\tests\unit;

use lujie\remote\user\RemoteUser;
use lujie\remote\user\tests\unit\mocks\TestRemoteUserClient;
use Yii;

class RemoteUserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        Yii::$app->set('remoteUserClient', [
            'class' => TestRemoteUserClient::class
        ]);
        Yii::$app->cache->flush();

        $remoteUser = RemoteUser::findIdentity(123);
        $this->assertNull($remoteUser);

        $remoteUser = RemoteUser::findIdentityByAccessToken('token_123');
        $this->assertInstanceOf(RemoteUser::class, $remoteUser);
        $this->assertEquals(123, $remoteUser->getId());
        $this->assertTrue($remoteUser->validateAuthKey('auth_key_123'));

        $remoteUser = RemoteUser::findIdentity(123);
        $this->assertInstanceOf(RemoteUser::class, $remoteUser);
        $this->assertEquals(123, $remoteUser->getId());
    }
}
