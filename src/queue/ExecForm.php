<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

use yii\base\Model;
use yii\queue\cli\Queue;

/**
 * Class ExecForm
 * @package lujie\extend\queue\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecForm extends Model
{
    public const EXEC_DONE = 0;

    public const EXEC_RETRY = 3;

    /**
     * @var Queue
     */
    public $queue = Queue::class;

    public $id;
    public $ttr;
    public $attempt;
    public $pid;
    public $message;

    public $result;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['id', 'ttr', 'attempt', 'pid'], 'integer'],
        ];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function exec(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $execute = $this->queue->execute($this->id, $this->message, $this->ttr, $this->attempt, $this->pid);
        $this->result = $execute ? self::EXEC_DONE : self::EXEC_RETRY;
        return true;
    }
}
