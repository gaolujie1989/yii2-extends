<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\base\Arrayable;
use yii\base\BaseObject;

/**
 * Class Progress
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Progress extends BaseObject
{
    /**
     * @var int
     */
    public $done = 0;

    /**
     * @var int
     */
    public $total = 0;

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var bool
     */
    public $break = false;
}