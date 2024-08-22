<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\ExecuteEvent;
use lujie\executing\Executor;
use lujie\executing\QueuedEvent;
use lujie\extend\helpers\ExceptionHelper;
use Yii;
use yii\base\Behavior;
use yii\base\UserException;
use yii\httpclient\Exception;

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
     * @var bool
     */
    public $profiling = false;

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
        if ($this->profiling) {
            Yii::beginProfile($title, Executor::class);
        }
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function afterExec(ExecuteEvent $event): void
    {
        $title = $this->getExecutableTitle($event->executable);
        if ($this->profiling) {
            Yii::endProfile($title, Executor::class);
        }
        $exception = $event->error;
        if ($exception) {
            if ($exception instanceof UserException) {
                Yii::info("$title is finished by {$exception->getMessage()}", Executor::class);
            } else {
                $error = $exception->getMessage();
                if ($exception instanceof Exception && str_contains($error, 'Curl error')) {
                    Yii::warning(["$title is finished with $error.", $exception], $event->executable::class);
                } else {
                    Yii::error(["$title is finished with error: $error.", $exception], $event->executable::class);
                }
            }
        } else {
            Yii::info("$title is finished.", Executor::class);
        }
        if ($this->autoFlush) {
            Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param ExecutableInterface $executable
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
