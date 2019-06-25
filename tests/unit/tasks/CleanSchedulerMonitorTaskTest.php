<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\tasks;

use lujie\data\loader\ArrayDataLoader;
use lujie\executing\monitor\behaviors\ActiveRecordMonitorBehavior;
use lujie\executing\monitor\behaviors\DbMonitorBehavior;
use lujie\scheduling\tasks\CleanSchedulerMonitorTask;
use lujie\scheduling\Scheduler;

class CleanSchedulerMonitorTaskTest extends \Codeception\Test\Unit
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
        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
            ],
        ]);
        $task = new CleanSchedulerMonitorTask(['scheduler' => $scheduler]);
        $task->execute();
    }
}
