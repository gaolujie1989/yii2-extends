<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\executing;

use Generator;

/**
 * Interface SubTaskInterface
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface FollowTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldFollowTask(): bool;

    /**
     * @return array|Generator
     * @inheritdoc
     */
    public function createFollowTasks(): array|Generator;
}
