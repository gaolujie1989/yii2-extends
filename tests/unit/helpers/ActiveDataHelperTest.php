<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\helpers\ActiveDataHelper;
use lujie\extend\tests\unit\fixtures\models\Migration;

class ActiveDataHelperTest extends \Codeception\Test\Unit
{
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
        $migration = [
            [
                'version' => 123456789,
                'apply_time' => '123456789',
                'xxx' => 'xxx',
            ]
        ];
        $typecast = ActiveDataHelper::typecast(Migration::class, $migration);
        $this->assertIsString($typecast[0]['version']);
        $this->assertIsInt($typecast[0]['apply_time']);
    }
}
