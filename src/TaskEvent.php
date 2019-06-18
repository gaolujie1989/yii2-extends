<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use yii\base\Event;

/**
 * Class TaskEvent
 *
 * @property Scheduler $sender
 *
 * @package lujie\scheduling\components
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskEvent extends Event
{
    /**
     * default false, if set true skip execute task
     * @var bool
     */
    public $executed = false;

    /**
     * @var TaskInterface
     */
    public $task;

    /**
     * @var \Exception
     */
    public $error;
}
