<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\tests\unit\mocks\MockActiveRecord;
use lujie\extend\validators\StringValidator;
use yii\validators\StringValidator as YiiStringValidator;

class LinkerValidatorTest extends \Codeception\Test\Unit
{


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
            'version' => 'test_version',
            'apply_time' => $now,
        ]);
        $migration->save(false);

        MockActiveRecord::$rules = [
            [['mock_id'], 'linker', 'targetClass' => Migration::class, 'targetAttribute' => 'version', 'linkAttributes' => ['apply_time' => 'mock_value']]
        ];
        $mockRecord = new MockActiveRecord();
        $mockRecord->mock_id = 'test_version';
        $this->assertNull($mockRecord->mock_value);
        $mockRecord->validate();
        $this->assertEquals($now, $mockRecord->mock_value);

        MockActiveRecord::$columns = ['version', 'apply_time'];
        MockActiveRecord::$rules = [
            [['version'], 'linker', 'targetClass' => Migration::class, 'linkAttributes' => ['apply_time']]
        ];
        $mockRecord = new MockActiveRecord();
        $mockRecord->version = 'test_version';
        $this->assertNull($mockRecord->apply_time);
        $mockRecord->validate();
        $this->assertEquals($now, $mockRecord->apply_time);
    }
}
