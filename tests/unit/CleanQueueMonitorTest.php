<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tests\unit;

use lujie\queuing\monitor\behaviors\ActiveRecordJobMonitorBehavior;
use lujie\queuing\monitor\behaviors\ActiveRecordWorkerMonitorBehavior;
use lujie\queuing\monitor\tasks\CleanQueueMonitorTask;
use Yii;
use yii\queue\file\Queue;
use yii\queue\serializers\JsonSerializer;

/**
 * Class ActiveRecordWorkerMonitorBehaviorTest
 * @package lujie\queuing\monitor\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CleanQueueMonitorTest extends \Codeception\Test\Unit
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
        $task = new CleanQueueMonitorTask(['queue' => $this->getQueue()]);
        $task->execute();
    }
}
