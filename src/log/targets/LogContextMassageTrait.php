<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\targets;

/**
 * Trait LogContextMassageTrait
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait LogContextMassageTrait
{
    /**
     * @var bool If true context message will be added to the end of output
     */
    public $enableContextMassage = false;

    /**
     * @inheritdoc
     * @return string
     */
    protected function getContextMessage(): string
    {
        return $this->enableContextMassage ? parent::getContextMessage() : '';
    }
}
