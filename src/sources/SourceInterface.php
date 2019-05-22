<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

/**
 * Interface SourceInterface
 * @package lujie\data\exchange\sources
 */
interface SourceInterface
{
    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array;
}
