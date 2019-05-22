<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

/**
 * Interface DbPipelineInterface
 * @package lujie\data\exchange\pipelines
 */
interface DbPipelineInterface extends PipelineInterface
{
    public const AFFECTED_CREATED = 'created';
    public const AFFECTED_UPDATED = 'updated';
    public const AFFECTED_SKIPPED = 'skipped';

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
