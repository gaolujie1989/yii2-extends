<?php

namespace lujie\extend\tests\unit;

use lujie\extend\tests\unit\mocks\MockRestClient;
use yii\helpers\VarDumper;
use yii\httpclient\MockTransport;
use yii\httpclient\Response;

class RestClientTraitTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $mockRestClient = new MockRestClient([
            'apiBaseUrl' => 'https://xxx/rest',
            'authUrl' => 'login',
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
        $mockTransport = $mockRestClient->getHttpClient()->getTransport();

        $expected = [
            'listTasks' => ['GET', 'project/tasks.json'],
            'getTask' => ['GET', 'project/tasks/{id}.json'],
            'createTask' => ['POST', 'project/tasks.json'],
            'updateTask' => ['PUT', 'project/tasks/{code}.json'],
            'runTask' => ['POST', 'project/tasks/{code}/run.json'],
            'xxxStatus' => ['GET', 'status'],
        ];
        $this->assertEquals($expected, $mockRestClient->apiMethods, VarDumper::dumpAsString($mockRestClient->apiMethods));

        $response = new Response([
            'headers' => ['http-code' => '200'],
            'data' => ['xxx' => 'xxx']
        ]);
        $mockTransport->appendResponse($response);
        $data = ['code' => 'xxx', 'status' => 1];
        $mockRestClient->updateTask($data);
        $requests = $mockTransport->flushRequests();
        $this->assertEquals('project/tasks/xxx.json', $requests[0]->getUrl());
        $data['access_token'] = 'mocked_token';
        $this->assertEquals($data, $requests[0]->getData());
    }
}
