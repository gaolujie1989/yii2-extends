<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\ExecuteEvent;
use lujie\executing\Executor;
use lujie\executing\QueuedEvent;
use Yii;
use yii\base\Behavior;

/**
 * Class LogBehavior
 *
 * @property Executor $owner;
 *
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LogBehavior extends Behavior
{
    /**
     * @var bool
     */
    public $autoFlush = true;

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Executor::EVENT_AFTER_QUEUED => 'afterQueued',
            Executor::EVENT_BEFORE_EXEC => 'beforeExec',
            Executor::EVENT_AFTER_EXEC => 'afterExec',
        ];
    }

    /**
     * @param QueuedEvent $event
     * @inheritdoc
     */
    public function afterQueued(QueuedEvent $event): void
    {
        $title = $this->getExecutableTitle($event->job->executable);
        Yii::info("$title is pushed.", Executor::class);
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function beforeExec(ExecuteEvent $event): void
    {
        $title = $this->getExecutableTitle($event->executable);
        Yii::info("$title is started.", Executor::class);
        Yii::beginProfile($title, Executor::class);
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function afterExec(ExecuteEvent $event): void
    {
        $title = $this->getExecutableTitle($event->executable);
        Yii::endProfile($title, Executor::class);
        if ($event->error) {
            $error = $event->error->getMessage() . "\n" . $event->error->getTraceAsString();
            Yii::info("$title is finished with error: $error.", Executor::class);
        } else {
            Yii::info("$title is finished.", Executor::class);
        }
        if ($this->autoFlush) {
            Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param QueuedEvent $event
     * @return string
     * @inheritdoc
     */
    protected function getExecutableTitle(ExecutableInterface $executable): string
    {
        return strtr('[{id}] {name} [{execUid}]', [
            '{id}' => $executable->getId(),
            '{execUid}' => $executable->getExecUid(),
            '{name}' => get_class($executable),
        ]);
    }
}
