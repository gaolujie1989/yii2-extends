<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;


interface ConditionSourceInterface extends SourceInterface
{
    /**
     * @param array $condition
     * @inheritdoc
     */
    public function setCondition(array $condition): void;
}
