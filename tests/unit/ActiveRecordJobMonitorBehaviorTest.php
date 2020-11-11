<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tests\unit;

use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use lujie\queuing\monitor\behaviors\ActiveRecordJobMonitorBehavior;
use lujie\queuing\monitor\models\QueueJob;
use lujie\queuing\monitor\models\QueueJobExec;
use lujie\queuing\monitor\tests\unit\mocks\TestJob;
use Yii;
use yii\base\Component;
use yii\console\Request;
use yii\helpers\VarDumper;
use yii\queue\file\Queue;
use yii\queue\serializers\JsonSerializer;

/**
 * Class ActiveRecordJobMonitorBehaviorTest
 * @package lujie\queuing\monitor\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordJobMonitorBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        QueueJob::deleteAll([]);
        QueueJobExec::deleteAll([]);
    }

    protected function _after()
    {
    }

    /**
     * @return Queue
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getQueue($name = 'testQueue'): Queue
    {
        $queue = new Queue([
            'serializer' => JsonSerializer::class,
            'as jobMonitor' => [
                'class' => ActiveRecordJobMonitorBehavior::class
            ],
        ]);
        Yii::$app->set($name, $queue);
        Yii::$app->get($name);
        $queue->clear();
        return $queue;
    }

    /**
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testAfterPush(): void
    {
        $queue = $this->getQueue();

        $now = time();
        $job = new TestJob();
        $jobId = $queue->push($job);
        $queueJob = QueueJob::findOne(['job_id' => $jobId]);
        $this->assertNotNull($queueJob);
        $attributes = $queueJob->getAttributes(['queue', 'job_id', 'job', 'ttr', 'delay', 'last_exec_at', 'last_exec_status']);
        $expected = [
            'queue' => 'testQueue',
            'job_id' => $jobId,
            'job' => $queue->serializer->serialize($job),
            'ttr' => 300,
            'delay' => 0,
            'last_exec_at' => 0,
            'last_exec_status' => ExecStatusConst::EXEC_STATUS_PENDING
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJob->pushed_at >= $now);
        $jobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNull($jobExec);
    }

    /**
     * @param $jobId
     * @param $status
     * @param $startedAt
     * @inheritdoc
     */
    protected function assertJobExecuted($jobId, $status, $startedAt, $queue = 'testQueue')
    {
        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotNull($queueJobExec);
        $attributes = $queueJobExec->getAttributes(['queue', 'job_id', 'worker_pid', 'attempt', 'status']);
        $expected = [
            'queue' => $queue,
            'job_id' => $jobId,
            'worker_pid' => getmypid(),
            'attempt' => 1,
            'status' => $status,
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJobExec->started_at >= $startedAt);
        $this->assertTrue($queueJobExec->finished_at >= $queueJobExec->started_at);

        $queueJob = QueueJob::findOne(['job_id' => $jobId]);
        $this->assertEquals($queueJobExec->finished_at, $queueJob->last_exec_at);
        $this->assertEquals($queueJobExec->status, $queueJob->last_exec_status);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecJobSuccess(): void
    {
        $queue = $this->getQueue();

        $now = time();
        Yii::$app->params['xxx'] = null;
        $job = new TestJob();
        $jobId = $queue->push($job);
        $queue->run(false);
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $this->assertJobExecuted($jobId, ExecStatusConst::EXEC_STATUS_SUCCESS, $now);

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertEmpty($queueJobExec->error);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecJobFail(): void
    {
        $queue = $this->getQueue();

        $now = time();
        Yii::$app->params['xxx'] = null;
        $job = new TestJob([
            'throwEx' => true
        ]);
        $jobId = $queue->push($job);
        $queue->run(false);
        $this->assertNull(Yii::$app->params['xxx']);

        $this->assertJobExecuted($jobId, ExecStatusConst::EXEC_STATUS_FAILED, $now);

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotEmpty($queueJobExec->error);
    }

    public function testExecTimeout(): void
    {
        /** @var Queue $queue */
        $queue = Yii::$app->queue;
        $queue->attachBehavior('jobMonitor', [
            'class' => ActiveRecordJobMonitorBehavior::class
        ]);
        $queue->clear();

        $now = time();
        Yii::$app->params['xxx'] = null;
        $job = new TestJob([
            'sleep' => 5
        ]);
        $jobId = $queue->ttr(2)->push($job);
        $queue->bootstrap(Yii::$app);
        $_SERVER['SCRIPT_FILENAME'] = 'apps/yii_test';
        Yii::$app->handleRequest(new Request(['params' => ['queue/run']]));

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotNull($queueJobExec);
        $attributes = $queueJobExec->getAttributes(['queue', 'job_id', 'worker_pid', 'attempt', 'status']);
        $expected = [
            'queue' => 'queue',
            'job_id' => $jobId,
            'worker_pid' => getmypid(),
            'attempt' => 1,
            'status' => ExecStatusConst::EXEC_STATUS_FAILED,
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJobExec->finished_at >= $queueJobExec->started_at);

        $queueJob = QueueJob::findOne(['job_id' => $jobId]);
        $this->assertEquals($queueJobExec->finished_at, $queueJob->last_exec_at);
        $this->assertEquals($queueJobExec->status, $queueJob->last_exec_status);

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotEmpty($queueJobExec->error);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function testCleanJobAndExec(): void
    {
        $queue = $this->getQueue();
        /** @var ActiveRecordJobMonitorBehavior $behavior */
        $behavior = $queue->getBehavior('jobMonitor');
        $behavior->cleanJobAndExec(true);
    }
}
