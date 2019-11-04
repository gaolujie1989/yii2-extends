<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\helpers\ModelRuleHelper;

class ModelRuleHelperTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rules = [
            [['version', 'apply_time'], 'required'],
            [['apply_time'], 'integer', 'min' => 0],
        ];
        $excepted = [
            [['version'], 'required'],
        ];
        $this->assertEquals($excepted, ModelRuleHelper::removeAttributesRules($rules, 'apply_time'));
    }
}
