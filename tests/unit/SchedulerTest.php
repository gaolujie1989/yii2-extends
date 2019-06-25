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
                        'executable' => [
                            'class' => TestTask::class,
                        ],
                    ],
                    'testTask2' => [
                        'expression' => '*/2 * * * *',
                        'executable' => [
                            'class' => TestTask::class,
                            'yKey' => '222',
                            'yValue' => '222',
                        ]
                    ],
                    'testTask3' => [
                        'expression' => '*/3 * * * *',
                        'executable' => [$this, 'forTestTask']
                    ],
                    'testTask4' => [
                        'expression' => '*/4 * * * *',
                        'executable' => $testTask
                    ]
                ]
            ]
        ]);

        /** @var CronTask[] $cronTasks */
        $cronTasks = [
            'testTask1' => new CronTask([
                'id' => 'testTask1',
                'expression' => '* * * * *',
                'executable' => [
                    'class' => TestTask::class
                ],
            ]),
            'testTask2' => new CronTask([
                'id' => 'testTask2',
                'expression' => '*/2 * * * *',
                'executable' => [
                    'class' => TestTask::class,
                    'yKey' => '222',
                    'yValue' => '222',
                ],
            ]),
            'testTask3' => new CronTask([
                'id' => 'testTask3',
                'expression' => '*/3 * * * *',
                'executable' => [$this, 'forTestTask']
            ]),
            'testTask4' => new CronTask([
                'id' => 'testTask4',
                'expression' => '*/4 * * * *',
                'executable' => $testTask
            ])
        ];
        $tasks = $scheduler->getTasks();
        $this->assertEquals($cronTasks, $tasks);

        foreach ($cronTasks as $cronTask) {
            $this->assertEquals($cronTask, $scheduler->getTask($cronTask->getId()));
        }

        $m = date('i');
        $dueTasks = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($m % $i === 0) {
                $taskId = 'testTask' . $i;
                $dueTasks[] = $cronTasks[$taskId];
            }
        }
        $tasks = $scheduler->getDueTasks();
        $this->assertEquals($dueTasks, $tasks);
    }
}
