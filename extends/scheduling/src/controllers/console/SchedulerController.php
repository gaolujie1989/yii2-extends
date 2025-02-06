<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\controllers\console;

use lujie\executing\ExecutableInterface;
use lujie\executing\TimeStepProgressTrait;
use lujie\extend\helpers\ClassHelper;
use lujie\scheduling\Scheduler;
use Yii;
use yii\base\UserException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
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
     * @var bool
     */
    public $usage = false;

    /**
     * @param $actionID
     * @return string[]
     * @inheritdoc
     */
    public function options($actionID): array
    {
        $options = [];
        if ($actionID === 'execute' || $actionID === 'handle' || $actionID === 'e' || $actionID === 'h') {
            $options = ['usage'];
        }
        return array_merge(parent::options($actionID), $options);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function optionAliases(): array
    {
        return array_merge(parent::optionAliases(), [
            'u' => 'usage',
        ]);
    }

    /**
     * @param string $d
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionIndex(string $d = ''): void
    {
        $tasks = $this->scheduler->getTasks();
        if ($d) {
            $this->stdout(VarDumper::dumpAsString($tasks));
        } else {
            $this->stdout(VarDumper::dumpAsString(array_keys($tasks)));
        }
    }

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionHandle(string $taskCode): void
    {
        $args = func_get_args();
        array_shift($args);
        $task = $this->scheduler->getTask($taskCode, $args);
        if ($this->usage) {
            $this->stdout(VarDumper::dumpAsString($task->getParams()));
            return;
        }
        $this->scheduler->handle($task);
    }

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute(string $taskCode): void
    {
        $args = func_get_args();
        array_shift($args);
        $task = $this->scheduler->getTask($taskCode, $args);
        if ($this->usage) {
            $this->stdout(VarDumper::dumpAsString($task->getParams()));
            return;
        }
        $this->scheduler->execute($task);
    }

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionH(string $taskCode): void
    {
        $args = func_get_args();
        array_shift($args);
        $task = $this->scheduler->getTask($taskCode, $args);
        if ($this->usage) {
            $this->stdout(VarDumper::dumpAsString($task->getParams()));
            return;
        }
        $this->scheduler->handle($task);
    }

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionE(string $taskCode): void
    {
        $args = func_get_args();
        array_shift($args);
        $task = $this->scheduler->getTask($taskCode, $args);
        if ($this->usage) {
            $this->stdout(VarDumper::dumpAsString($task->getParams()));
            return;
        }
        $this->scheduler->execute($task);
    }
}
