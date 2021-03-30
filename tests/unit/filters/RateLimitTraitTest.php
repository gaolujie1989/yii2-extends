<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\mocks\MockRateLimitIdentity;
use Yii;
use yii\base\Action;
use yii\base\Controller;
use yii\filters\RateLimiter;
use yii\filters\RateLimitInterface;
use yii\web\Request;
use yii\web\Response;
use yii\web\TooManyRequestsHttpException;

class RateLimitTraitTest extends \Codeception\Test\Unit
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

    protected function getRateLimitUser()
    {
        return new MockRateLimitIdentity([
            'rateLimit' => 10,
            'rateWindow' => 10,
            'rateLimits' => [
                'read' => [
                    'rateLimit' => 5,
                    'rateWindow' => 5,
                    'rateKey' => 'read',
                ],
                'write' => [
                    'rateLimit' => 2,
                    'rateWindow' => 2,
                    'rateKey' => 'write',
                ],
                'testC/testA' => [
                    'rateLimit' => 1,
                    'rateWindow' => 1,
                    'rateKey' => 'test',
                ]
            ]
        ]);
    }

    /**
     * @throws \yii\web\TooManyRequestsHttpException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rateLimit = 10;
        $rateWindow = 10;
        $identity = new MockRateLimitIdentity([
            'rateLimit' => $rateLimit,
            'rateWindow' => $rateWindow,
        ]);
        $this->checkRateLimit($identity, $rateLimit, $rateWindow);
    }

    /**
     * @throws \yii\web\TooManyRequestsHttpException
     * @inheritdoc
     */
    public function testAction(): void
    {
        $identity = $this->getRateLimitUser();
        $this->checkRateLimit($identity, 1, 1, 'testC', 'testA');
    }

    /**
     * @throws \yii\web\TooManyRequestsHttpException
     * @inheritdoc
     */
    public function testWrite(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $identity = $this->getRateLimitUser();
        $this->checkRateLimit($identity, 2, 2);
    }

    /**
     * @throws \yii\web\TooManyRequestsHttpException
     * @inheritdoc
     */
    public function testRead(): void
    {
        $identity = $this->getRateLimitUser();
        $this->checkRateLimit($identity, 5, 5);
    }

    /**
     * @param RateLimiter $rateLimiter
     * @param RateLimitInterface $identity
     * @param int $rateLimit
     * @param int $rateWindow
     * @throws TooManyRequestsHttpException
     * @inheritdoc
     */
    protected function checkRateLimit(
        RateLimitInterface $identity,
        int $rateLimit,
        int $rateWindow,
        $controllerId = 'testController',
        $actionId = 'testAction'
    )
    {
        $rateLimiter = new RateLimiter([
            'enableRateLimitHeaders' => false,
        ]);

        $request = new Request();
        $response = new Response();
        $controller = new Controller($controllerId, Yii::$app);
        $action = new Action($actionId, $controller);

        list($limit, $window) = $identity->getRateLimit($request, $action);
        $this->assertEquals($rateLimit, $limit);
        $this->assertEquals($rateWindow, $window);

        for ($i = 0; $i < $rateLimit; $i++) {
            $rateLimiter->checkRateLimit($identity, $request, $response, $action);
        }
        $this->expectException(TooManyRequestsHttpException::class);
        $rateLimiter->checkRateLimit($identity, $request, $response, $action);
    }
}
