<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\validators\NumberValidator;
use yii\validators\NumberValidator as YiiNumberValidator;

class NumberValidatorTest extends \Codeception\Test\Unit
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
        $migration = new Migration([
            'version' => '123456789',
        ]);
        $validator = new NumberValidator(['integerOnly' => true]);
        $validator->validateAttribute($migration, 'version');
        $this->assertFalse($migration->hasErrors('version'));
        $this->assertTrue($migration->version === 123456789);

        $migration = new Migration([
            'version' => '123456789',
        ]);
        $yiiValidator = new YiiNumberValidator();
        $yiiValidator->validateAttribute($migration, 'version');
        $this->assertFalse($migration->hasErrors('version'));
        $this->assertTrue($migration->version === '123456789');
    }
}
