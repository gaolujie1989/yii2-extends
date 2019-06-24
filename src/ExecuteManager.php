<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ComponentHelper;
use yii\base\Component;
use yii\di\Instance;
use yii\mutex\Mutex;
use yii\queue\Queue;

/**
 * Class ExecuteManager
 * @package lujie\execute
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecuteManager extends Component
{
    public const EVENT_BEFORE_EXEC = 'beforeExec';
    public const EVENT_AFTER_EXEC = 'afterExec';
    public const EVENT_AFTER_SKIP = 'afterSkip';

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @param $key
     * @return bool|string|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function handle(ExecutableInterface $executable)
    {
        if ($executable instanceof QueueableInterface && $executable->shouldQueued()) {
            return $this->handleQueueable($executable);
        }
        return $this->execute($executable);
    }

    /**
     * @param QueueableInterface $queueable
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    private function handleQueueable(QueueableInterface $queueable): ?string
    {
        /** @var Queue $queue */
        $queue = Instance::ensure($queueable->getQueue() ?: $this->queue);
        $job = new ExecutableJob([
            'executeManager' => ComponentHelper::getName($this),
            'executable' => $queueable,
        ]);
        if ($queueable->getTtr()) {
            $job->ttr = $queueable->getTtr();
        }
        if ($queueable->getAttempts()) {
            $job->attempts = $queueable->getAttempts();
        }
        return $queue->push($job);
    }

    /**
     * @param ExecutableInterface $executable
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(ExecutableInterface $executable): bool
    {
        $event = new ExecuteEvent(['executable' => $executable]);

        if ($executable instanceof LockableInterface && $executable->shouldLock()) {
            $mutexName = $executable->getLockKey();
            /** @var Mutex $mutex */
            $mutex = Instance::ensure($executable->getMutex() ?: $this->mutex, Mutex::class);
            if (!$mutex->acquire($mutexName, $executable->getTimeout())) {
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }
        }

        try {
            $this->trigger(self::EVENT_BEFORE_EXEC, $event);
            if ($event->executed) {
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }

            $event->result = $event->executable->execute();

            $this->trigger(self::EVENT_AFTER_EXEC, $event);
            return true;
        } catch (\Throwable $e) {
            $event->error = $e;
            $this->trigger(self::EVENT_AFTER_EXEC, $event);

            return false;
        } finally {
            if (isset($mutex, $mutexName)
                && $executable instanceof LockableInterface && $executable->shouldLock()) {
                $mutex->release($mutexName);
            }
        }
    }
}
