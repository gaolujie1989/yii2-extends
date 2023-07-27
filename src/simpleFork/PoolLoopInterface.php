<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\simpleFork;

use Jenner\SimpleFork\AbstractPool;

/**
 * Interface PoolLoopInterface
 * @package lujie\extend\simpleFork
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface PoolLoopInterface
{
    public function loop(AbstractPool $pool): void;
}
