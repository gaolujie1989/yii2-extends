<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\helpers\ObjectHelper;
use lujie\extend\tests\unit\mocks\MockIdentity;

class ObjectHelperTest extends \Codeception\Test\Unit
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
        $identityData = [
            'id' => 1,
            'authKey' => 'auth_key_111'
        ];
        $excepted = new MockIdentity($identityData);
        $identity = ObjectHelper::create($identityData, [], MockIdentity::class);
        $this->assertEquals($excepted, $identity);

        $identity = ObjectHelper::create(
            ['id' => ':id', 'authKey' => ':authKey'],
            $identityData,
            MockIdentity::class,
        );
        $this->assertEquals($excepted, $identity);
    }
}
