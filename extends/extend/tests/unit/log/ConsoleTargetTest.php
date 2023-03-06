<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\log;

use lujie\extend\log\targets\ConsoleTarget;
use yii\log\Logger;

class ConsoleTargetTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $messageText = 'Test Log Message';
        $level = Logger::LEVEL_INFO;
        $category = 'test';
        $time = time();
        $traces = ['Test Log Trace1', 'Test Log Trace2'];
        $memory = memory_get_usage();
        $message = [$messageText, $level, $category, $time, $traces, $memory];
        $target = new ConsoleTarget();
        $formatMessage = $target->formatMessage($message);

        $memoryUsage = number_format(($memory) / 1024 / 1024, 2) . ' MB';
        $datetime = date($target->dateFormat, $time);
        $excepted = str_pad("[{$datetime}][info][{$memoryUsage}][{$category}]", $target->labelPadSize, ' ')
            . ' ' . $messageText;
        $this->assertEquals($excepted, $formatMessage);
    }
}
