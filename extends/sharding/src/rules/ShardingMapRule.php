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
class ShardingMapRule extends BaseShardingRule
{
    /**
     * @var array
     */
    public $map = [];

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
        return $this->map[$value] ?? reset($this->map);
    }
}