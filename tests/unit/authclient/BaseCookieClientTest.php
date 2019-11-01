<?php

namespace lujie\extend\tests\unit;

use lujie\extend\tests\unit\mocks\MockCookieClient;
use yii\helpers\VarDumper;
use yii\httpclient\MockTransport;
use yii\httpclient\Response;

class BaseCookieClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \lujie\extend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $mockCookieClient = new MockCookieClient([
            'loginUrl' => 'https://xxx/login',
            'loginData' => ['xxxId' => 'xxx123'],
            'username' => 'u123',
            'password' => 'p123456',
        ]);
        /** @var MockTransport $mockTransport */
        $mockTransport = $mockCookieClient->getHttpClient()->getTransport();

        $cookies = [
            'sessionId' => ['name' => 'sessionId', 'value' => 'session_id_xxx_123456'],
        ];
        $loginResponse = new Response([
            'content' => 'Mocked Content',
        ]);
        $loginResponse->addCookies($cookies);
        $mockTransport->appendResponse($loginResponse);
        $mockCookieClient->login();
        $this->assertEquals($cookies['sessionId']['value'], $mockCookieClient->getCookies()->get('sessionId'));
        $requests = $mockTransport->flushRequests();
        $expected = [
            'xxxId' => 'xxx123',
            'username' => 'u123',
            'password' => 'p123456',
        ];
        $this->assertEquals($expected, $requests[0]->getData());

        $actionResponse = new Response([
            'content' => 'Mocked Content'
        ]);
        $mockTransport->appendResponse($actionResponse);
        $mockCookieClient->request('do-action');
        $requests = $mockTransport->flushRequests();
        $this->assertEquals($cookies['sessionId']['value'], $requests[0]->getCookies()->get('sessionId'));
    }
}
