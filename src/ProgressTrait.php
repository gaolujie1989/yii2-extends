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
     * @return Progress
     * @inheritdoc
     */
    public function getProgress(): Progress
    {
        if ($this->progress === null) {
            $this->progress = new Progress();
        }
        return $this->progress;
    }
}