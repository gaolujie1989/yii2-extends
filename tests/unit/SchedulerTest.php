<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\remote\rbac\RemoteAccessChecker;
use lujie\remote\rbac\tests\unit\mocks\TestRemoteManagerClient;
use lujie\remote\user\RemoteUser;
use lujie\remote\user\tests\unit\mocks\TestRemoteUserClient;
use lujie\scheduling\CronTask;
use lujie\scheduling\Scheduler;
use lujie\scheduling\tests\unit\mocks\TestTask;
use Yii;

class SchedulerTest extends \Codeception\Test\Unit
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

    public function forTestTask(): void
    {
        Yii::$app->params['xxx'] = 'xxx';
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testGetTasks(): void
    {
        $testTask = static function() {
            Yii::$app->params['xxx'] = 'xxx';
        };
        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'testTask1' => [
                        'expression' => '* * * * *',
                        'callable' => [
                            'class' => TestTask::class,
                        ],
                    ],
                    'testTask2' => [
                        'expression' => '*/2 * * * *',
                        'callable' => [
                            'class' => TestTask::class,
                            'yKey' => '222',
                            'yValue' => '222',
                        ]
                    ],
                    'testTask3' => [
                        'expression' => '*/3 * * * *',
                        'callable' => [$this, 'forTestTask']
                    ],
                    'testTask4' => [
                        'expression' => '*/4 * * * *',
                        'callable' => $testTask
                    ]
                ]
            ]
        ]);

        /** @var CronTask[] $cronTasks */
        $cronTasks = [
            'testTask1' => new CronTask(['data' => [
                'taskCode' => 'testTask1',
                'expression' => '* * * * *',
                'callable' => [
                    'class' => TestTask::class
                ],
            ]]),
            'testTask2' => new CronTask(['data' => [
                'taskCode' => 'testTask2',
                'expression' => '*/2 * * * *',
                'callable' => [
                    'class' => TestTask::class,
                    'yKey' => '222',
                    'yValue' => '222',
                ],
            ]]),
            'testTask3' => new CronTask(['data' => [
                'taskCode' => 'testTask3',
                'expression' => '*/3 * * * *',
                'callable' => [$this, 'forTestTask']
            ]]),
            'testTask4' => new CronTask(['data' => [
                'taskCode' => 'testTask4',
                'expression' => '*/4 * * * *',
                'callable' => $testTask
            ]])
        ];
        $tasks = $scheduler->getTasks();
        $this->assertEquals($cronTasks, $tasks);

        foreach ($cronTasks as $cronTask) {
            $this->assertEquals($cronTask, $scheduler->getTask($cronTask->getTaskCode()));
        }
    }
}
