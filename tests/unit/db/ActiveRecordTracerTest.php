<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\db\ActiveRecordTracer;
use lujie\extend\tests\unit\mocks\MockActiveRecord;

class ActiveRecordTracerTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testMe(): void
    {
        $activeRecordTracer = new ActiveRecordTracer();
        $activeRecordTracer->bootstrap(null);
        $mockActiveRecord = new MockActiveRecord();
        $mockActiveRecord->beforeSave(true);
        $expected = [
            'created_by' => 0,
            'created_at' => time(),
            'updated_by' => 0,
            'updated_at' => time(),
        ];
        $attributes = $mockActiveRecord->getAttributes(['created_by', 'created_at', 'updated_by', 'updated_at']);
        $this->assertEquals($expected, $attributes);

        $mockActiveRecord = new MockActiveRecord();
        $mockActiveRecord->beforeSave(false);
        $expected = [
            'created_by' => null,
            'created_at' => null,
            'updated_by' => 0,
            'updated_at' => time(),
        ];
        $attributes = $mockActiveRecord->getAttributes(['created_by', 'created_at', 'updated_by', 'updated_at']);
        $this->assertEquals($expected, $attributes);
    }
}
