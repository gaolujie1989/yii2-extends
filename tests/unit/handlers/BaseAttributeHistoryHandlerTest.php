<?php

namespace lujie\ar\history\behaviors\tests\unit;

use lujie\ar\history\handlers\BaseAttributeHistoryHandler;

class BaseAttributeHistoryHandlerTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testMe(): void
    {
        $handler = new BaseAttributeHistoryHandler();
        $handler->maxValueLength = 6;
        $this->assertNull($handler->diff('ABC', 'ABC'));
        $this->assertEquals(['modified' => "'Abc' -> 'Bcd'"], $handler->diff('Abc', 'Bcd'));
        $this->assertEquals(['modified' => "'Abc...' -> 'Bcd...'"], $handler->diff('Abc123456789', 'Bcd123456789'));
    }
}
