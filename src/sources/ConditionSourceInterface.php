<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;


interface ConditionSourceInterface extends SourceInterface
{
    /**
     * @param $condition
     * @inheritdoc
     */
    public function setCondition($condition): void;
}
