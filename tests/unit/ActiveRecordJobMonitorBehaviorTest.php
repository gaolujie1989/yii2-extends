<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tests\unit;

use lujie\queuing\monitor\behaviors\ActiveRecordJobMonitorBehavior;
use lujie\queuing\monitor\behaviors\BaseJobMonitorBehavior;
use lujie\queuing\monitor\models\QueueJob;
use lujie\queuing\monitor\models\QueueJobExec;
use lujie\queuing\monitor\tests\unit\mocks\TestJob;
use Yii;
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
            'job' => json_encode([
                'class' => TestJob::class,
                'yKey' => 'xxx',
                'yValue' => 'xxx',
                'throwEx' => false,
            ]),
            'ttr' => 300,
            'delay' => 0,
            'last_exec_at' => 0,
            'last_exec_status' => BaseJobMonitorBehavior::EXEC_STATUS_PENDING
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJob->pushed_at >= $now);
        $jobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNull($jobExec);
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

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotNull($queueJobExec);
        $attributes = $queueJobExec->getAttributes(['queue', 'job_id', 'worker_pid', 'attempt', 'status']);
        $expected = [
            'queue' => 'testQueue',
            'job_id' => $jobId,
            'worker_pid' => getmypid(),
            'attempt' => 1,
            'status' => BaseJobMonitorBehavior::EXEC_STATUS_SUCCESS,
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJobExec->started_at >= $now);
        $this->assertTrue($queueJobExec->finished_at >= $queueJobExec->started_at);
        $this->assertEmpty($queueJobExec->error);

        $queueJob = QueueJob::findOne(['job_id' => $jobId]);
        $this->assertEquals($queueJobExec->finished_at, $queueJob->last_exec_at);
        $this->assertEquals($queueJobExec->status, $queueJob->last_exec_status);
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

        $queueJobExec = QueueJobExec::findOne(['job_id' => $jobId]);
        $this->assertNotNull($queueJobExec);
        $attributes = $queueJobExec->getAttributes(['queue', 'job_id', 'worker_pid', 'attempt', 'status']);
        $expected = [
            'queue' => 'testQueue',
            'job_id' => $jobId,
            'worker_pid' => getmypid(),
            'attempt' => 1,
            'status' => BaseJobMonitorBehavior::EXEC_STATUS_FAILED,
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueJobExec->started_at >= $now);
        $this->assertTrue($queueJobExec->finished_at >= $queueJobExec->started_at);
        $this->assertNotEmpty($queueJobExec->error);

        $queueJob = QueueJob::findOne(['job_id' => $jobId]);
        $this->assertEquals($queueJobExec->finished_at, $queueJob->last_exec_at);
        $this->assertEquals($queueJobExec->status, $queueJob->last_exec_status);
    }
}
