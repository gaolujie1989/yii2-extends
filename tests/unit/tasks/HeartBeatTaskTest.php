<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\tasks;

use lujie\scheduling\tasks\HeartBeatTask;
use yii\base\InvalidConfigException;

class HeartBeatTaskTest extends \Codeception\Test\Unit
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
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $task = new HeartBeatTask();
        $task->execute();
        $this->assertEquals('HeatBeatTask:' . date('Y-m-d H:i:s'), $task->cache->get($task->cacheKey));
    }
}
