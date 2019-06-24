<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\base\Event;
use yii\queue\Queue;

/**
 * Class ExecuteEvent
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueuedEvent extends Event
{
    /**
     * default false, if set true skip to queue
     * @var bool
     */
    public $queued = false;

    /**
     * @var ExecutableJob
     */
    public $job;

    /**
     * @var Queue
     */
    public $queue;

    /**
     * @var string|int
     */
    public $jobId;
}
