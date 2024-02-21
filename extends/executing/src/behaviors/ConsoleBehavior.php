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
use yii\console\Application;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\VarDumper;

/**
 * Class LogBehavior
 *
 * @property Executor $owner;
 *
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConsoleBehavior extends Behavior
{
    /**
     * @var bool
     */
    public $autoFlush = true;

    public $displayMemoryUsage = true;

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        if (Yii::$app instanceof Application) {
            return [
                Executor::EVENT_BEFORE_EXEC => 'beforeExec',
                Executor::EVENT_AFTER_EXEC => 'afterExec',
                Executor::EVENT_UPDATE_PROGRESS => 'updateProgress',
            ];
        }
        return [];
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
        if ($event->error) {
            Console::error($event->error->getMessage());
            Console::output($event->error->getTraceAsString());
        }
        if ($event->result !== null) {
            Console::output(VarDumper::dumpAsString($event->result));
        }
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function updateProgress(ExecuteEvent $event): void
    {
        $progress = $event->progress;
        if ($progress) {
            if ($this->displayMemoryUsage) {
                $memoryUsage = '[' . round(memory_get_peak_usage() / 1024 / 1024) . 'M]';
                Console::updateProgress($progress->done, $progress->total, $memoryUsage . $progress->message);
            } else {
                Console::updateProgress($progress->done, $progress->total, $progress->message);
            }
        }
    }
}
