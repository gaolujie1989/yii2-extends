<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tests\unit;

use lujie\queuing\monitor\behaviors\ActiveRecordJobMonitorBehavior;
use lujie\queuing\monitor\behaviors\ActiveRecordWorkerMonitorBehavior;
use lujie\queuing\monitor\models\QueueWorker;
use lujie\queuing\monitor\tests\unit\mocks\TestJob;
use Yii;
use yii\queue\file\Queue;
use yii\queue\serializers\JsonSerializer;

/**
 * Class ActiveRecordWorkerMonitorBehaviorTest
 * @package lujie\queuing\monitor\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordWorkerMonitorBehaviorTest extends \Codeception\Test\Unit
{
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
            'as workerMonitor' => [
                'class' => ActiveRecordWorkerMonitorBehavior::class
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
    public function testMe(): void
    {
        $queue = $this->getQueue();

        $now = time();
        $job = new TestJob();
        $queue->push($job);
        $job = new TestJob([
            'throwEx' => true
        ]);
        $queue->push($job);

        $queue->run(false);

        $pid = getmypid();
        $queueWorker = QueueWorker::findOne(['pid' => $pid]);
        $this->assertNotNull($queueWorker);
        $attributes = $queueWorker->getAttributes(['queue', 'success_count', 'failed_count']);
        $expected = [
            'queue' => 'testQueue',
            'success_count' => 1,
            'failed_count' => 1
        ];
        $this->assertEquals($expected, $attributes);
        $this->assertTrue($queueWorker->started_at >= $now);
        $this->assertTrue($queueWorker->finished_at >= $queueWorker->started_at);
        $this->assertTrue($queueWorker->pinged_at >= $queueWorker->started_at);
    }
}
