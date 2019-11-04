<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\httpclient\RateLimitCheckerBehavior;
use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\tests\unit\mocks\MockTransport;
use lujie\extend\validators\DateValidator;
use yii\httpclient\Client;
use yii\httpclient\Response;

class DateValidatorTest extends \Codeception\Test\Unit
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
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $now = time();
        $migration = new Migration([
            'apply_time' => date('Y-m-d H:i:s', $now),
        ]);
        $validator = new DateValidator();
        $validator->validateAttribute($migration, 'apply_time');
        $this->assertFalse($migration->hasErrors('apply_time'));
        $this->assertEquals($now, $migration->apply_time);
    }
}
