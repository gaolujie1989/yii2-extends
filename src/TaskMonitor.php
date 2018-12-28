<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\BaseActiveRecord;
use yii\db\Connection;

/**
 * Class TaskMonitor
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskMonitor extends Behavior
{
    /**
     * @var BaseActiveRecord
     */
    public $taskRecordClass;

    /**
     * @var BaseActiveRecord
     */
    public $execRecordClass;

    /**
     * @var BaseActiveRecord
     */
    protected $taskRecord;

    /**
     * @var BaseActiveRecord
     */
    protected $execRecord;

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        return [
            Scheduler::EVENT_BEFORE_EXEC => 'beforeExec',
            Scheduler::EVENT_AFTER_EXEC => 'afterExec',
            Scheduler::EVENT_AFTER_ERROR => 'afterError',
            Scheduler::EVENT_AFTER_SKIP => 'afterSkip',
        ];
    }

    /**
     * @param TaskEvent $event
     * @throws \Throwable
     * @inheritdoc
     */
    public function beforeExec(TaskEvent $event)
    {
        $this->getTaskRecord($event);

        $callback = function() use ($event) {
            $task = $event->task;
            $data = [
                'scheduler' => $event->sender->getName(),
                'started_at' => time(),
                'finished_at' => 0,
                'skipped_at' => 0,
                'next_run_at' => $task->getNextRunTime()->getTimestamp(),
                'error' => '',
            ];
            $this->execRecord = new $this->execRecordClass(['task_code' => $task->getTaskCode()]);
            $this->execRecord->setAttributes($data);
            $this->execRecord->save(false);
            $execId = $this->execRecord->getPrimaryKey();

            if ($this->taskRecord) {
                $data = [
                    'last_exec_id' => $execId,
                ];
                $this->taskRecord->setAttributes($data);
                $this->taskRecord->save(false);
            }
        };

        $db = $this->execRecordClass::getDb();
        if  ($db instanceof Connection) {
            $db->transaction($callback);
        } else {
            call_user_func($callback);
        }
    }

    /**
     * @param TaskEvent $event
     * @throws Exception
     * @inheritdoc
     */
    public function afterExec(TaskEvent $event)
    {
        $taskCode = $event->task->getTaskCode();
        if (!$this->execRecord) {
            Yii::error("Empty execRecord of task {$taskCode}, unknown error.", __METHOD__);
            $this->execRecord = $this->execRecord ?: $this->execRecordClass::findOne(['task_code' => $taskCode, 'finished_at' => 0]);
        }
        if (!$this->execRecord) {
            throw new Exception("Empty execRecord of task {$taskCode}, unknown error.");
        }

        $now = time();
        $data = [
            'finished_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
        ];
        $this->execRecord->setAttributes($data);
        $this->execRecord->save(false);
    }

    /**
     * @param TaskErrorEvent $event
     * @throws Exception
     * @inheritdoc
     */
    public function afterError(TaskErrorEvent $event)
    {
        $taskCode = $event->task->getTaskCode();
        if (!$this->execRecord) {
            Yii::error("Empty execRecord of task {$taskCode}, unknown error.", __METHOD__);
            $this->execRecord = $this->execRecord ?: $this->execRecordClass::findOne(['task_code' => $taskCode, 'finished_at' => 0]);
        }
        if (!$this->execRecord) {
            throw new Exception("Empty execRecord of task {$taskCode}, unknown error.");
        }

        $now = time();
        $data = [
            'finished_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'error' => strval($event->error),
        ];
        $this->execRecord->setAttributes($data);
        $this->execRecord->save(false);
    }

    /**
     * @param TaskEvent $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function afterSkip(TaskEvent $event)
    {
        $task = $event->task;
        $this->execRecord = $this->execRecord ?: new $this->execRecordClass([
            'task_code' => $event->task->getTaskCode(),
            'scheduler' => $event->sender->getName(),
            'started_at' => time(),
            'finished_at' => 0,
            'next_run_at' => $task->getNextRunTime()->getTimestamp(),
            'error' => '',
        ]);
        $data = [
            'skipped_at' => time(),
        ];
        $this->execRecord->setAttributes($data);
        $this->execRecord->save(false);
    }

    /**
     * @param TaskEvent $event
     * @return mixed
     * @inheritdoc
     */
    public function getTaskRecord(TaskEvent $event)
    {
        $condition = ['task_code' => $event->task->getTaskCode()];
        return $this->taskRecord = $this->taskRecordClass::findOne($condition) ?: new $this->taskRecordClass($condition);
    }
}