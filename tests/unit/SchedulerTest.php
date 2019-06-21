<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\scheduling\CronTask;
use lujie\scheduling\Scheduler;
use lujie\scheduling\tests\unit\mocks\TestOverlappingTask;
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

        $m = date('i');
        $dueTasks = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($m % $i === 0) {
                $taskCode = 'testTask' . $i;
                $dueTasks[] = $cronTasks[$taskCode];
            }
        }
        $tasks = $scheduler->getDueTasks();
        $this->assertEquals($dueTasks, $tasks);
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testHandleTasks(): void
    {
        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'testTask1' => [
                        'expression' => '* * * * *',
                        'callback' => [
                            'class' => TestTask::class,
                        ],
                    ],
                ]
            ]
        ]);
        Yii::$app->params['xxx'] = null;
        $this->assertTrue($scheduler->handleTask($scheduler->getTask('testTask1')));
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'testTask1' => [
                        'class' => TestOverlappingTask::class,
                        'expression' => '* * * * *',
                        'callback' => [
                            'class' => TestTask::class,
                        ],
                        'shouldQueued' => true,
                        'queue' => 'queue',
                    ],
                ]
            ]
        ]);
        //set scheduler app component
        Yii::$app->set('scheduler', $scheduler);
        Yii::$app->get('scheduler');
        $task = $scheduler->getTask('testTask1');
        $jobId = $scheduler->handleTask($task);
        $this->assertTrue($task->getQueue()->isWaiting($jobId));

        $scheduler = new Scheduler([
            'taskLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'testTask1' => [
                        'class' => TestOverlappingTask::class,
                        'expression' => '* * * * *',
                        'callback' => [
                            'class' => TestOverlappingTask::class,
                        ],
                        'isWithoutOverlapping' => true,
                    ],
                ]
            ]
        ]);
        $this->assertTrue($scheduler->handleTask($scheduler->getTask('testTask1')));
        $this->assertFalse($scheduler->handleTask($scheduler->getTask('testTask1')));
    }
}
