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
class FillDefaultValueTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $defaultValues = [];

    /**
     * @param array $data
     * @return array|null
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map(function($values) {
            $emptyValues = array_filter($values, [$this, 'isEmpty']);
            $fillValues = array_intersect_key($this->defaultValues, $emptyValues);
            $values = array_merge($values, $fillValues);
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
