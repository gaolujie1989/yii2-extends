<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;

/**
 * Interface SourceInterface
 * @package lujie\data\exchange\sources
 */
interface BatchSourceInterface extends SourceInterface
{
    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch(int $batchSize = 100): Iterator;

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each(int $batchSize = 100): Iterator;
}
