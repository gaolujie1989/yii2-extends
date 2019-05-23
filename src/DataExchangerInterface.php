<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\sources\SourceInterface;

/**
 * Interface ExchangerInterface
 * @package lujie\data\exchange
 */
interface DataExchangerInterface
{
    /**
     * @param SourceInterface $source
     * @return bool
     * @inheritdoc
     */
    public function exchange(SourceInterface $source): bool;
}
