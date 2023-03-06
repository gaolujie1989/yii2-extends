<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

/**
 * Interface ProgressInterface
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface ProgressInterface
{
    /**
     * @return Progress
     * @inheritdoc
     */
    public function getProgress(): Progress;
}