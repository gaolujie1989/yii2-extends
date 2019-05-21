<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;

/**
 * Class FillPreValueTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FillPreValueTransformer extends BaseObject implements TransformerInterface
{
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
     * @return array|void
     * @inheritdoc
     */
    public function transform(array $data)
    {
        $preValues = [];
        $flipOnlyKeys = array_flip($this->onlyKeys);
        $flipExcludeKeys = array_flip($this->excludeKeys);
        return array_map(function($values) use (&$preValues, $flipOnlyKeys, $flipExcludeKeys) {
            $emptyValues = array_filter($values, [$this, 'isEmpty']);
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
     * @param $value
     * @return bool
     * @inheritdoc
     */
    public function isEmpty($value): bool
    {
        return $value === null || (is_string($value) && $value === '') || (is_array($value) && count($value) === 0);
    }
}
