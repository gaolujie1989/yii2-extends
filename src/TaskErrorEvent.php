<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


/**
 * Class TaskErrorEvent
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskErrorEvent extends TaskEvent
{
    public $error;
}