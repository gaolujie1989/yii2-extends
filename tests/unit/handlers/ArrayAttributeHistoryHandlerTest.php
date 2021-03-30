<?php

namespace lujie\ar\history\behaviors\tests\unit;

use lujie\ar\history\handlers\ArrayAttributeHistoryHandler;
use yii\helpers\VarDumper;

class ArrayAttributeHistoryHandlerTest extends \Codeception\Test\Unit
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
    public function testWithKey(): void
    {
        $handler = new ArrayAttributeHistoryHandler();
        $handler->withKey = true;
        $diff = $handler->diff(['A' => 'AAA', 'B' => 'BBB'], ['B' => 'BBB', 'A' => 'AAA']);
        $this->assertNull($diff);
        $diff = $handler->diff(['A' => 'AAA', 'B' => 'BBB'], ['A' => 'BBB', 'C' => 'CCC']);
        $expected = [
            'added' => ['C' => 'CCC'],
            'deleted' => ['B' => 'BBB'],
            'modified' => ['A' => '"AAA" -> "BBB"'],
        ];
        $this->assertEquals($expected, $diff, VarDumper::dumpAsString($diff));
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testWithoutKey(): void
    {
        $handler = new ArrayAttributeHistoryHandler();
        $handler->withKey = false;
        $diff = $handler->diff(['A' => 'AAA', 'B' => 'BBB'], ['A' => 'BBB', 'C' => 'CCC']);
        $expected = [
            'added' => ['CCC'],
            'deleted' => ['AAA'],
        ];
        $this->assertEquals($expected, $diff);
    }
}
