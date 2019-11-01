<?php

namespace lujie\extend\tests\unit;

use lujie\extend\tests\unit\mocks\MockCookieClient;
use lujie\extend\tests\unit\mocks\MockOAuth2Client;
use yii\helpers\VarDumper;
use yii\httpclient\MockTransport;
use yii\httpclient\Response;

class RestOAuth2ClientTest extends \Codeception\Test\Unit
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
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $mockOAuth2Client = new MockOAuth2Client([
            'apiBaseUrl' => 'https://xxx/rest',
            'tokenUrl' => 'login',
            'resources' => [
                'Task' => 'project/tasks'
            ],
            'actions' => [
                'list' => ['GET', ''],
                'get' => ['GET', '{id}'],
                'create' => ['POST', ''],
                'update' => ['PUT', '{id}'],
            ],
            'extraActions' => [
                'Task' => [
                    'update' => ['PUT', '{code}'],
                    'run' => ['POST', '{code}/run'],
                ]
            ],
            'suffix' => '.json',
            'apiMethods' => [
                'xxxStatus' => ['GET', 'status'],
            ]
        ]);
        /** @var MockTransport $mockTransport */
        $mockTransport = $mockOAuth2Client->getHttpClient()->getTransport();

        $expected = [
            'listTasks' => ['GET', 'project/tasks.json'],
            'getTask' => ['GET', 'project/tasks/{id}.json'],
            'createTask' => ['POST', 'project/tasks.json'],
            'updateTask' => ['PUT', 'project/tasks/{code}.json'],
            'runTask' => ['POST', 'project/tasks/{code}/run.json'],
            'xxxStatus' => ['GET', 'status'],
        ];
        $this->assertEquals($expected, $mockOAuth2Client->apiMethods, VarDumper::dumpAsString($mockOAuth2Client->apiMethods));

        $response = new Response([
            'headers' => ['http-code' => '200'],
            'data' => ['xxx' => 'xxx']
        ]);
        $mockTransport->appendResponse($response);
        $data = ['code' => 'xxx', 'status' => 1];
        $mockOAuth2Client->updateTask($data);
        $requests = $mockTransport->flushRequests();
        $this->assertEquals('project/tasks/xxx.json', $requests[0]->getUrl());
        $data['access_token'] = 'mocked_token';
        $this->assertEquals($data, $requests[0]->getData());
    }
}
