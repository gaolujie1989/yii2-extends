<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\tasks;

use lujie\data\loader\ArrayDataLoader;
use lujie\scheduling\Scheduler;
use lujie\scheduling\tasks\CleanSchedulerMonitorTask;

class CleanSchedulerMonitorTaskTest extends \Codeception\Test\Unit
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
        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
            ],
        ]);
        $task = new CleanSchedulerMonitorTask(['scheduler' => $scheduler]);
        $task->execute();
    }
}
