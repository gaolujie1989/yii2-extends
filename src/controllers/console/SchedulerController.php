<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\controllers\console;

use lujie\scheduling\Scheduler;
use Yii;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class SchedulerCommand
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SchedulerController extends Controller
{
    /**
     * @var Scheduler
     */
    public $scheduler = 'scheduler';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
    }

    /**
     * @inheritdoc
     */
    public function actionRunAlways(): void
    {
        while (true) {
            $sec = (int)date('s');
            if ($sec < 5) {
                try {
                    $this->scheduler->run();

                    $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2); //MB
                    $this->stdout("Memory usage {$memoryUsage} MB\n");
                    if ($memoryUsage > 100) {
                        break;
                    }
                } catch (\Throwable $e) {
                    Yii::error($e, __METHOD__);
                } finally {
                    $sec = (int)date('s');
                    sleep(60 - $sec);
                }
            } else {
                sleep(60 - $sec);
            }
        }
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionRun(): void
    {
        $this->scheduler->run();
    }

    /**
     * @param string $taskCode
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionHandle(string $taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->handle($task);
    }

    /**
     * @param string $taskCode
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionExecute(string $taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->execute($task);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionTasks(): void
    {
        $tasks = $this->scheduler->getTasks();
        $this->stdout(VarDumper::dumpAsString($tasks));
    }
}
