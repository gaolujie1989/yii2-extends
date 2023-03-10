<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\behaviors;

use lujie\extend\helpers\ComponentHelper;
use yii\base\Behavior;
use yii\queue\cli\Queue as CliQueue;
use yii\queue\cli\WorkerEvent;

/**
 * Class BaseWorkerMonitorBehavior
 * @package lujie\queuing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseWorkerMonitorBehavior extends Behavior
{
    /**
     * @var bool
     */
    public $listenWorkerLoop = true;

    /**
     * @var int
     */
    public $workerPingInterval = 15;

    /**
     * @var int
     */
    private $lastPingedAt = 0;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        $events = [
            CliQueue::EVENT_WORKER_START => 'workerStart',
            CliQueue::EVENT_WORKER_STOP => 'workerStop',
        ];
        if ($this->listenWorkerLoop) {
            $events[CliQueue::EVENT_WORKER_LOOP] = 'workerLoop';
        }
        return $events;
    }

    /**
     * @param WorkerEvent $event
     * @inheritdoc
     */
    public function workerStart(WorkerEvent $event): void
    {
        $data = [
            'queue' => ComponentHelper::getName($event->sender),
            'pid' => $event->sender->getWorkerPid(),
            'started_at' => time(),
        ];
        $this->saveWorkerRecord($data);
    }

    /**
     * @param WorkerEvent $event
     * @inheritdoc
     */
    public function workerLoop(WorkerEvent $event): void
    {
        $now = time();
        if ($now - $this->lastPingedAt < $this->workerPingInterval) {
            return;
        }

        $data = [
            'pinged_at' => $now
        ];
        $this->saveWorkerRecord($data);
        $this->lastPingedAt = $now;
    }

    /**
     * @param WorkerEvent $event
     * @inheritdoc
     */
    public function workerStop(WorkerEvent $event): void
    {
        $data = [
            'finished_at' => time()
        ];
        $this->saveWorkerRecord($data);
    }

    abstract protected function saveWorkerRecord(array $data): void;

    abstract public function updateCount(int $workerPid, bool $success): void;
}
