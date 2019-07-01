<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;


interface ConditionSourceInterface extends SourceInterface
{
    /**
     * @return array
     * @inheritdoc
     */
    public function getCondition(): array;

    /**
     * @param $condition
     * @inheritdoc
     */
    public function setCondition($condition): void;
}
