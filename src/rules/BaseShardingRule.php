<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\rules;

use yii\base\BaseObject;

/**
 * Class ShardingRule
 * @package lujie\sharding\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseShardingRule extends BaseObject
{
    /**
     * @var string
     */
    public $separator = '';

    /**
     * @param $value
     * @return string
     * @inheritdoc
     */
    public function getSuffix($value): string
    {
        return $this->separator . $this->getSuffixInternal($value);
    }

    /**
     * @param $value
     * @return string
     * @inheritdoc
     */
    abstract protected function getSuffixInternal($value): string;
}