<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\rules;

/**
 * Class ShardingRule
 * @package lujie\sharding\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShardingHashRule extends BaseShardingRule
{
    /**
     * @var int
     */
    public $count = 4;

    /**
     * @param $value
     * @return string
     * @inheritdoc
     */
    protected function getSuffixInternal($value): string
    {
        if (is_array($value)) {
            $value = reset($value);
        }
        return $value % $this->count;
    }
}