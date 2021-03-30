<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\validators\DateValidator;

class DateValidatorTest extends \Codeception\Test\Unit
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
        $now = time();
        $migration = new Migration([
            'apply_time' => date('c', $now),
        ]);
        $validator = new DateValidator();
        $validator->validateAttribute($migration, 'apply_time');
        $this->assertFalse($migration->hasErrors('apply_time'));
        $this->assertEquals($now, $migration->apply_time);
    }
}
