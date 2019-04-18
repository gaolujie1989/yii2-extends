<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use yii\queue\Queue;

interface QueuedTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldQueued();

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
