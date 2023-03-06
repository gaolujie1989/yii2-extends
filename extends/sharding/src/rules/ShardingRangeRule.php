<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\rules;

use yii\base\InvalidArgumentException;

/**
 * Class ShardingRule
 * @package lujie\sharding\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShardingRangeRule extends BaseShardingRule
{
    /**
     * ['xxx' => ['from', 'to']]
     * @var array
     */
    public $ranges = [];

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
        foreach ($this->ranges as $key => [$from, $to]) {
            if ($from <= $value && $value < $to) {
                return $key;
            }
        }
        throw new InvalidArgumentException('Value not in ranges');
    }
}