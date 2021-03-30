<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\filters\auth\HttpCookieAuth;
use lujie\extend\tests\unit\mocks\MockIdentity;
use yii\web\Cookie;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\User;
use yii\web\UserEvent;

class HttpCookieAuthTest extends \Codeception\Test\Unit
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
     * @throws \yii\web\UnauthorizedHttpException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $cookieAuth = new HttpCookieAuth();
        $user = new User([
            'identityClass' => MockIdentity::class,
            'enableSession' => false,
        ]);
        //console user login has exceptions, mock login set identity
        $user->on(User::EVENT_BEFORE_LOGIN, static function (UserEvent $event) use ($user) {
            $user->setIdentity($event->identity);
            $event->isValid = false;
        });

        $request = new Request([
            'enableCsrfCookie' => false,
            'enableCookieValidation' => false,
        ]);
        $response = new Response();
        $cookieCollection = $request->getCookies();
        $cookieCollection->readOnly = false;

        $this->assertNull($cookieAuth->authenticate($user, $request, $response));

        $cookieCollection->add(new Cookie([
            'name' => $cookieAuth->cookie,
            'value' => 'access_token_111'
        ]));
        $expected = new MockIdentity(['id' => 1, 'authKey' => 'auth_key_111']);
        $this->assertEquals($expected, $cookieAuth->authenticate($user, $request, $response));

        $cookieCollection->remove($cookieAuth->cookie);
        $cookieCollection->add(new Cookie([
            'name' => $cookieAuth->cookie,
            'value' => 'access_token_invalid'
        ]));
        $this->expectException(UnauthorizedHttpException::class);
        $this->assertNull($cookieAuth->authenticate($user, $request, $response));
    }
}
