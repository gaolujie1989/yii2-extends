<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\helpers\TemplateHelper;

class TemplateHelperTest extends \Codeception\Test\Unit
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
        $template = 'Value1: {key1},Value2: {data.key2}';
        $params = [
            'key1' => 'v1',
            'data' => [
                'key2' => 'v2'
            ],
        ];
        $excepted = 'Value1: v1,Value2: v2';
        $this->assertEquals($excepted, TemplateHelper::render($template, $params));
    }
}
