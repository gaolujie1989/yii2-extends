<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

/**
 * Interface As2MessageProcessor
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface As2MessageProcessorInterface
{
    public function process(string $content): bool;
}