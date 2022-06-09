<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

/**
 * Trait ProgressTrait
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ProgressTrait
{
    /**
     * @var Progress
     */
    private $progress;

    /**
     * @param int|null $total
     * @return Progress
     * @inheritdoc
     */
    public function getProgress(?int $total = null): Progress
    {
        if ($this->progress === null) {
            $this->progress = new Progress();
        }
        if ($total) {
            $this->progress->total = $total;
        }
        return $this->progress;
    }
}