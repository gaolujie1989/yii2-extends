<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\tests\unit;

use lujie\extend\constants\StatusConst;
use lujie\user\forms\LoginForm;
use lujie\user\models\User;
use lujie\user\tests\unit\fixtures\UserFixture;
use Yii;
use yii\helpers\VarDumper;
use yii\web\UserEvent;

class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::$app->set('user', [
            'class' => \yii\web\User::class,
            'enableSession' => false,
            'identityClass' => User::class,
            'on beforeLogin' => static function (UserEvent $event) {
                /** @var \yii\web\User $user */
                $user = $event->sender;
                $user->setIdentity($event->identity);
                $event->isValid = false;
            }
        ]);
    }

    protected function _after()
    {
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function _fixtures(): array
    {
        return [
            'user' => UserFixture::class
        ];
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $loginForm = new LoginForm();
        $loginForm->username = 'test_user2';
        $loginForm->password = 'pass1234';
        $this->assertFalse($loginForm->login());
        $this->assertTrue($loginForm->hasErrors('password'));

        $loginForm = new LoginForm();
        $loginForm->username = 'test_user2';
        $loginForm->password = 'pass123';
        $this->assertFalse($loginForm->login());
        $this->assertTrue($loginForm->hasErrors('username'));

        $loginForm = new LoginForm();
        $loginForm->username = 'test_user1';
        $loginForm->password = 'pass123';
        $this->assertTrue($loginForm->login(), VarDumper::dumpAsString($loginForm->getErrors()));
        $accessToken = $loginForm->getAccessToken();
        $user = User::findIdentityByAccessToken($accessToken);
        $this->assertNotNull($user);
        $this->assertEquals($user->username, $loginForm->username);

        $user->status = StatusConst::STATUS_INACTIVE;
        $user->save(false);
        $this->assertNull(User::findIdentityByAccessToken($accessToken));
    }
}
