<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\simpleFork;

use Jenner\SimpleFork\AbstractPool;

/**
 * Class PoolLoop
 * @package lujie\extend\simpleFork
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PoolLoop implements PoolLoopInterface
{
    /**
     * @var callable
     */
    public $callable;

    /**
     * @param $callable
     */
    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param AbstractPool $pool
     * @inheritdoc
     */
    public function loop(AbstractPool $pool): void
    {
        call_user_func($this->callable, $pool);
    }
}
