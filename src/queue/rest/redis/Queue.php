<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue\rest\redis;

use lujie\extend\queue\WebQueueTrait;

/**
 * Class Queue
 * @package lujie\extend\queue\rest\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Queue extends \yii\queue\redis\Queue
{
    use WebQueueTrait;

    /**
     * @var string command class name
     */
    public $commandClass = Command::class;
}