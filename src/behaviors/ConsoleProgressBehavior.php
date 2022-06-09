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
use yii\helpers\Console;

/**
 * Class LogBehavior
 *
 * @property Executor $owner;
 *
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConsoleProgressBehavior extends Behavior
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
            Executor::EVENT_BEFORE_EXEC => 'beforeExec',
            Executor::EVENT_AFTER_EXEC => 'afterExec',
            Executor::EVENT_UPDATE_PROGRESS => 'updateProgress',
        ];
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function beforeExec(ExecuteEvent $event): void
    {
        $progress = $event->progress;
        if ($progress) {
            Console::startProgress($progress->done, $progress->total, $progress->message);
        }
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function afterExec(ExecuteEvent $event): void
    {
        $progress = $event->progress;
        if ($progress) {
            Console::endProgress();
        }
    }

    /**
     * @param ExecuteEvent $event
     * @return string
     * @inheritdoc
     */
    public function updateProgress(ExecuteEvent $event): string
    {
        $progress = $event->progress;
        if ($progress) {
            Console::updateProgress($progress->done, $progress->total, $progress->message);
        }
    }
}
