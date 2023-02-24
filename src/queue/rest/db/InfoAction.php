<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue\rest\db;

use yii\db\Query;
use yii\queue\db\Queue;
use yii\rest\Action;

/**
 * Class InfoAction
 * @package lujie\extend\queue\rest\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class InfoAction extends Action
{
    /**
     * @var Queue
     */
    public $queue;

    /**
     * Info about queue status.
     */
    public function run(): array
    {
        return [
            'Jobs' => [
                'waiting' => $this->getWaiting()->count('*', $this->queue->db),
                'delayed' => $this->getDelayed()->count('*', $this->queue->db),
                'reserved' => $this->getReserved()->count('*', $this->queue->db),
                'done' => $this->getDone()->count('*', $this->queue->db),
            ]
        ];
    }

    #region COPY

    /**
     * @return Query
     */
    protected function getWaiting(): Query
    {
        return (new Query())
            ->from($this->queue->tableName)
            ->andWhere(['channel' => $this->queue->channel])
            ->andWhere(['reserved_at' => null])
            ->andWhere(['delay' => 0]);
    }

    /**
     * @return Query
     */
    protected function getDelayed(): Query
    {
        return (new Query())
            ->from($this->queue->tableName)
            ->andWhere(['channel' => $this->queue->channel])
            ->andWhere(['reserved_at' => null])
            ->andWhere(['>', 'delay', 0]);
    }

    /**
     * @return Query
     */
    protected function getReserved(): Query
    {
        return (new Query())
            ->from($this->queue->tableName)
            ->andWhere(['channel' => $this->queue->channel])
            ->andWhere('[[reserved_at]] is not null')
            ->andWhere(['done_at' => null]);
    }

    /**
     * @return Query
     */
    protected function getDone(): Query
    {
        return (new Query())
            ->from($this->queue->tableName)
            ->andWhere(['channel' => $this->queue->channel])
            ->andWhere('[[done_at]] is not null');
    }

    #endregion
}