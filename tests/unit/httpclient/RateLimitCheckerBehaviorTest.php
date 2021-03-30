<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\httpclient\RateLimitCheckerBehavior;
use lujie\extend\tests\unit\mocks\MockTransport;
use yii\httpclient\Client;
use yii\httpclient\Response;

class RateLimitCheckerBehaviorTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $client = new Client([
            'transport' => MockTransport::class,
            'as testRateLimitChecker' => [
                'class' => RateLimitCheckerBehavior::class,
                'limitHeader' => 'TEST-Limit',
                'remainingHeader' => 'TEST-Calls-Left',
                'resetHeader' => 'TEST-Decay',
            ],
        ]);
        /** @var MockTransport $transport */
        $transport = $client->getTransport();
        $response = new Response([
            'headers' => [
                'TEST-Limit' => 60,
                'TEST-Calls-Left' => 0,
                'TEST-Decay' => 3,
            ]
        ]);
        $transport->appendResponse($response);
        $transport->appendResponse($response);

        $client->send($client->createRequest());

        $startTime = time();
        $client->send($client->createRequest());
        $endTime = time();
        $time = $endTime - $startTime;
        $this->assertTrue($time >= 2 && $time <= 3);
    }
}
