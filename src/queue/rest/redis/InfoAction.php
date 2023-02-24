<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue\rest\redis;

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
        $prefix = $this->queue->channel;
        $waiting = $this->queue->redis->llen("$prefix.waiting");
        $delayed = $this->queue->redis->zcount("$prefix.delayed", '-inf', '+inf');
        $reserved = $this->queue->redis->zcount("$prefix.reserved", '-inf', '+inf');
        $total = $this->queue->redis->get("$prefix.message_id");
        $done = $total - $waiting - $delayed - $reserved;

        return [
            'Jobs' => [
                'waiting' => $waiting,
                'delayed' => $delayed,
                'reserved' => $reserved,
                'done' => $done,
            ]
        ];
    }
}