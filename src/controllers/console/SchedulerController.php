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

    public function options($actionID)
    {
        if ($actionID === 'execute-with-params') {
            return ['params'];
        }
        return parent::options($actionID);
    }

    public $params;

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionHandle(string $taskCode, ...$params): void
    {
        $task = $this->scheduler->getTask($taskCode, $params);
        $this->scheduler->handle($task);
    }

    /**
     * @param string $taskCode
     * @param ...$params
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute(string $taskCode, ...$params): void
    {
        $task = $this->scheduler->getTask($taskCode, $params);
        $this->scheduler->execute($task);
    }

    /**
     * @param string $taskCode
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionUsage(string $taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->stdout(VarDumper::dumpAsString($task->getParams()));
    }

    /**
     * @param string $taskCode
     * @param string $timeFrom
     * @param string $timeTo
     * @param int $timeStep
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionExecuteTimeStepped(string $taskCode, string $timeFrom = '-10 days', string $timeTo = 'now', int $timeStep = 864000): void
    {
        /** @var ExecutableInterface|TimeStepProgressTrait $task */
        $task = $this->scheduler->getTask($taskCode);
        if (!ClassHelper::useTrait($task, TimeStepProgressTrait::class)) {
            throw new UserException('Task must use TimeStepProgressTrait');
        }
        $task->timeFrom = $timeFrom;
        $task->timeTo = $timeTo;
        $task->timeStep = $timeStep;
        $this->scheduler->execute($task);
    }

    /**
     * @param string $d
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionTasks(string $d = ''): void
    {
        $tasks = $this->scheduler->getTasks();
        if ($d) {
            $this->stdout(VarDumper::dumpAsString($tasks));
        } else {
            $this->stdout(VarDumper::dumpAsString(array_keys($tasks)));
        }
    }
}
