<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\base\Event;

/**
 * Class ExecuteEvent
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecuteEvent extends Event
{
    /**
     * default false, if set true skip execute
     * @var bool
     */
    public $executed = false;

    /**
     * @var ExecutableInterface
     */
    public $executable;

    /**
     * @var \Exception|\Throwable|null
     */
    public $error;

    /**
     * @var mixed
     */
    public $result;

    /**
     * @var Progress|null
     */
    public $progress;
}
