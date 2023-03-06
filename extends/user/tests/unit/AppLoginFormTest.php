<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\tests\unit;

use lujie\extend\constants\StatusConst;
use lujie\user\forms\AppLoginForm;
use lujie\user\models\User;
use lujie\user\models\UserApp;
use lujie\user\tests\unit\fixtures\UserAppFixture;
use lujie\user\tests\unit\fixtures\UserFixture;
use Yii;
use yii\helpers\VarDumper;
use yii\web\UserEvent;

class AppLoginFormTest extends \Codeception\Test\Unit
{


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
            'user' => UserFixture::class,
            'userApp' => UserAppFixture::class,
        ];
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $appLoginForm = new AppLoginForm();
        $appLoginForm->key = 'app2.key';
        $appLoginForm->secret = 'app2.secret2';
        $this->assertFalse($appLoginForm->login());
        $this->assertTrue($appLoginForm->hasErrors('secret'));

        $appLoginForm = new AppLoginForm();
        $appLoginForm->key = 'app2.key';
        $appLoginForm->secret = 'app2.secret';
        $this->assertFalse($appLoginForm->login());
        $this->assertTrue($appLoginForm->hasErrors('key'));

        $appLoginForm = new AppLoginForm();
        $appLoginForm->key = 'app11.key';
        $appLoginForm->secret = 'app11.secret';
        $this->assertFalse($appLoginForm->login());
        $this->assertTrue($appLoginForm->hasErrors('key'));

        $appLoginForm = new AppLoginForm();
        $appLoginForm->key = 'app1.key';
        $appLoginForm->secret = 'app1.secret';
        $this->assertTrue($appLoginForm->login(), VarDumper::dumpAsString($appLoginForm->getErrors()));
        $accessToken = $appLoginForm->getAccessToken();
        $user = User::findIdentityByAccessToken($accessToken);
        $this->assertNotNull($user);

        $user->status = StatusConst::STATUS_INACTIVE;
        $user->save(false);
        $this->assertNull(User::findIdentityByAccessToken($accessToken));
        $user->status = StatusConst::STATUS_ACTIVE;
        $user->save(false);

        $appLoginForm = new AppLoginForm();
        $appLoginForm->key = 'app1.key';
        $appLoginForm->secret = 'app1.secret';
        $this->assertTrue($appLoginForm->login(), VarDumper::dumpAsString($appLoginForm->getErrors()));
        $accessToken = $appLoginForm->getAccessToken();
        $user = User::findIdentityByAccessToken($accessToken);
        $this->assertNotNull($user);

        $userApp = UserApp::find()->key($appLoginForm->key)->one();
        $userApp->status = StatusConst::STATUS_INACTIVE;
        $userApp->save(false);
        $this->assertTrue(User::findIdentityByAccessToken($accessToken) === null);
    }
}
