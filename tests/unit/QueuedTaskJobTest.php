<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;


use lujie\data\loader\ArrayDataLoader;
use lujie\scheduling\QueuedTaskJob;
use lujie\scheduling\Scheduler;
use lujie\scheduling\tests\unit\mocks\TestTask;
use Yii;

class QueuedTaskJobTest extends \Codeception\Test\Unit
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
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
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

        $job = new QueuedTaskJob([
            'scheduler' => $scheduler,
            'taskCode' => 'testTask1'
        ]);

        Yii::$app->params['xxx'] = null;
        $job->execute(null);
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }
}
