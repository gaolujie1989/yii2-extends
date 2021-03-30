<?php

namespace lujie\ar\history\behaviors\tests\unit;

use lujie\ar\history\handlers\BaseAttributeHistoryHandler;
use lujie\ar\history\handlers\MapAttributeHistoryHandler;
use lujie\data\loader\ArrayDataLoader;

class MapAttributeHistoryHandlerTest extends \Codeception\Test\Unit
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
        $handler = new MapAttributeHistoryHandler([
            'labelLoader' => new ArrayDataLoader([
                'data' => [
                    'a' => 'AAA',
                    'b' => 'BBB',
                ]
            ])
        ]);
        $this->assertEquals(['modified' => '"AAA" -> "BBB"'], $handler->diff('a', 'b'));
        $this->assertEquals(['modified' => '"AAA" -> "c"'], $handler->diff('a', 'c'));
    }
}
