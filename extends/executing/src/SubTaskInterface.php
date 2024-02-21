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
interface SubTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldSubTask(): bool;

    /**
     * @return array|Generator
     * @inheritdoc
     */
    public function createSubTasks(): array|Generator;
}
