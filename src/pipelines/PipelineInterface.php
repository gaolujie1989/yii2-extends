<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;


interface PipelineInterface
{
    public const AFFECTED_CREATED = 'created';
    public const AFFECTED_UPDATED = 'updated';
    public const AFFECTED_SKIPPED = 'skipped';

    /**
     * @param $data
     * @return mixed
     * @inheritdoc
     */
    public function process(array $data): bool;

    /**
     * @return array
     * @inheritdoc
     */
    public function getAffectedRowCounts(): array;

    /**
     * @return array
     * @inheritdoc
     */
    public function getErrors(): array;
}
