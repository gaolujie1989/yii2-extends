<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\tests\unit\monitor;

use lujie\executing\ExecuteEvent;
use lujie\executing\Executor;
use lujie\executing\monitor\behaviors\ActiveRecordMonitorBehavior;
use lujie\executing\monitor\behaviors\BaseMonitorBehavior;
use lujie\executing\monitor\models\ExecutableExec;
use lujie\executing\tests\unit\mocks\TestExecutable;
use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\di\Instance;
use yii\queue\Queue;

class ActiveRecordMonitorBehaviorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return Executor
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getExecutor(): Executor
    {
        $executor = new Executor();
        $executor->attachBehavior('monitor', [
            'class' => ActiveRecordMonitorBehavior::class
        ]);
        Yii::$app->set('executor', $executor);
        Yii::$app->get('executor', $executor);
        return $executor;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecuteSuccess(): void
    {
        $executor = $this->getExecutor();
        $executable = new TestExecutable();
        $executable->id = 1;

        $now = time();
        $this->assertTrue($executor->execute($executable));

        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'skipped_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_SUCCESS
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));
        $this->assertTrue($taskExec->started_at >= $now);
        $this->assertTrue($taskExec->finished_at >= $now);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecuteFailed(): void
    {
        $executor = $this->getExecutor();
        $executable = new TestExecutable();
        $executable->id = 1;
        $executable->throwEx = true;

        $now = time();
        $this->assertFalse($executor->execute($executable));

        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'skipped_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_FAILED
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));
        $this->assertTrue($taskExec->started_at >= $now);
        $this->assertTrue($taskExec->finished_at >= $now);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecuteSkipped(): void
    {
        $executor = $this->getExecutor();
        $executor->on(Executor::EVENT_BEFORE_EXEC, static function (ExecuteEvent $event) {
            $event->executed = true;
        });
        $executable = new TestExecutable();
        $executable->id = 1;

        $now = time();
        $this->assertFalse($executor->execute($executable));

        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'finished_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_SKIPPED
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));
        $this->assertTrue($taskExec->started_at >= $now);
        $this->assertTrue($taskExec->skipped_at >= $now);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecuteLockable(): void
    {
        $executor = $this->getExecutor();
        $executable = new TestExecutable();
        $executable->id = random_int(1, 1000);
        $executable->shouldLocked = true;

        $now = time();
        $this->assertTrue($executor->execute($executable));
        ExecutableExec::deleteAll([]);

        $executable->shouldLocked = true;
        $this->assertFalse($executor->execute($executable));

        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'started_at' => 0,
            'finished_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_SKIPPED
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));
        $this->assertTrue($taskExec->skipped_at >= $now);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecuteQueueable(): void
    {
        /** @var Queue $queue */
        $queue = Instance::ensure('queue', Queue::class);
        $executor = $this->getExecutor();
        $executable = new TestExecutable();
        $executable->id = 1;
        $executable->shouldQueued = true;

        $now = time();
        $jobId = $executor->handle($executable);
        $this->assertTrue($queue->isWaiting($jobId));

        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'skipped_at' => 0,
            'started_at' => 0,
            'finished_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_QUEUED
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));

        //execute
        $this->assertTrue($executor->execute($executable));

        $this->assertEquals(1, ExecutableExec::find()->count());
        $taskExec = ExecutableExec::findOne(['executable_id' => $executable->getId(), 'executor' => 'executor']);
        $expected = [
            'skipped_at' => 0,
            'status' => ExecStatusConst::EXEC_STATUS_SUCCESS
        ];
        $this->assertNotNull($taskExec);
        $this->assertEquals($expected, $taskExec->getAttributes(array_keys($expected)));
        $this->assertTrue($taskExec->started_at >= $now);
        $this->assertTrue($taskExec->finished_at >= $now);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testCleanExec(): void
    {
        $executor = $this->getExecutor();
        /** @var BaseMonitorBehavior $monitor */
        $monitor = $executor->getBehavior('monitor');
        $monitor->cleanExec(true);
    }
}
