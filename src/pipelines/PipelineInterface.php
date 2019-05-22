<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;


interface PipelineInterface
{
    /**
     * @param $data
     * @return mixed
     * @inheritdoc
     */
    public function process(array $data): bool;
}
