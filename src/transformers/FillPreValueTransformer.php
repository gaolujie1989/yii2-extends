<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use lujie\extend\helpers\ValueHelper;
use yii\base\BaseObject;

/**
 * Class FillPreValueTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FillPreValueTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var string
     */
    public $indexKey = '';

    /**
     * @var array
     */
    public $onlyKeys = [];

    /**
     * @var array
     */
    public $excludeKeys = [];

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $preValues = [];
        $flipOnlyKeys = array_flip($this->onlyKeys);
        $flipExcludeKeys = array_flip($this->excludeKeys);
        return array_map(function($values) use (&$preValues, $flipOnlyKeys, $flipExcludeKeys) {
            if (!$this->isOneGroup($values, $preValues)) {
                $preValues = $values;
                return $values;
            }
            $emptyValues = array_filter($values, static function ($value) {
                return ValueHelper::isEmpty($value);
            });
            $fillValues = array_intersect_key($preValues, $emptyValues);
            if ($flipOnlyKeys) {
                $fillValues = array_intersect_key($fillValues, $flipOnlyKeys);
            }
            if ($flipExcludeKeys) {
                $fillValues = array_diff_key($fillValues, $flipExcludeKeys);
            }
            $values = array_merge($values, $fillValues);
            $preValues = $values;
            return $values;
        }, $data);
    }

    /**
     * @param array $values
     * @param array $preValues
     * @return bool
     * @inheritdoc
     */
    public function isOneGroup(array $values, array $preValues): bool
    {
        if ($this->indexKey && isset($preValues[$this->indexKey])) {
            return empty($values[$this->indexKey]) || $values[$this->indexKey] === $preValues[$this->indexKey];
        }
        return true;
    }
}
