<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor;

use lujie\queuing\models\QueueWorker;
use Yii;
use yii\queue\cli\WorkerEvent;

/**
 * Class ActiveRecordWorkerMonitorBehavior
 * @package lujie\queuing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordWorkerMonitorBehavior extends BaseWorkerMonitorBehavior
{
    /**
     * @var QueueWorker
     */
    public $workerClass;

    /**
     * @var QueueWorker
     */
    private $worker;

    /**
     * @param $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveWorkerRecord($data)
    {
        if (!$this->worker) {
            $this->worker = Yii::createObject($this->workerClass);
        }
        $this->worker->setAttributes($data);
        $this->worker->save(false);
    }

    /**
     * @param bool $success
     * @inheritdoc
     */
    public function updateCount($success = true)
    {
        if ($this->worker) {
            $countAttribute = $success ? 'success_count' : 'failed_count';
            $this->worker->updateCounters([$countAttribute => 1]);
        }
    }

    /**
     * @param WorkerEvent $event
     * @inheritdoc
     */
    public function workerStop(WorkerEvent $event)
    {
        if (!$this->listenWorkerLoop) {
            $connection = $this->workerClass::getDb();
            if (method_exists($connection, 'close')) {
                $connection->close();
            }
        }
        parent::workerStop($event);
    }
}
