<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\rest;

use lujie\extend\rest\RestResponseBehavior;
use yii\web\Response;

class RestResponseBehaviorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $response = new Response([
            'format' => Response::FORMAT_JSON,
            'as rest' => [
                'class' => RestResponseBehavior::class,
                'alwaysStatusOK' => true,
                'enableCors' => false,
            ]
        ]);
        $response->statusCode = 201;
        $response->data = ['xxx' => '111'];
        $response->trigger(Response::EVENT_BEFORE_SEND);

        $excepted = [
            'status' => 201,
            'code' => 201,
            'data' => ['xxx' => '111'],
        ];
        $this->assertEquals($excepted, $response->data);
        $this->assertEquals(200, $response->statusCode);

        $response->statusCode = 500;
        $response->data = ['message' => 'xxx error'];
        $response->trigger(Response::EVENT_BEFORE_SEND);
        $excepted = [
            'status' => 500,
            'code' => 500,
            'message' => 'xxx error'
        ];
        $this->assertEquals($excepted, $response->data);
        $this->assertEquals(200, $response->statusCode);
    }
}
