<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

interface PipelineInterface
{
    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function process(array $data): bool;
}
