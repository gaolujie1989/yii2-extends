<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\helpers\ComponentHelper;
use Yii;
use yii\base\Component;

class ComponentHelperTest extends \Codeception\Test\Unit
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
        $component = new Component();
        Yii::$app->set('testComponent', $component);
        Yii::$app->get('testComponent');
        $this->assertEquals('testComponent', ComponentHelper::getName($component));
    }
}
