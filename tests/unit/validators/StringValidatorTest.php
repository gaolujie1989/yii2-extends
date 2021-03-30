<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\validators\StringValidator;
use yii\validators\StringValidator as YiiStringValidator;

class StringValidatorTest extends \Codeception\Test\Unit
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
            'version' => 123456789,
        ]);
        $validator = new StringValidator();
        $validator->validateAttribute($migration, 'version');
        $this->assertFalse($migration->hasErrors('version'));
        $this->assertTrue($validator->validate(123456789));

        $migration = new Migration([
            'version' => 123456789,
        ]);
        $yiiValidator = new YiiStringValidator();
        $yiiValidator->validateAttribute($migration, 'version');
        $this->assertTrue($migration->hasErrors('version'));
        $this->assertFalse($yiiValidator->validate(123456789));
    }
}
