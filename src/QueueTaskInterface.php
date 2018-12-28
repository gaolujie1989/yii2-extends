<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use yii\queue\Queue;

interface QueueTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldQueue();

    /**
     * @return Queue
     * @inheritdoc
     */
    public function getQueue();

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr();

    /**
     * @return mixed
     * @inheritdoc
     */
    public function getAttempts();
}