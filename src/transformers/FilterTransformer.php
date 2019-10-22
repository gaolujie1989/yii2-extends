<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;


use yii\base\BaseObject;

/**
 * Class IndexFilterTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FilterTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var string
     */
    public $filterKey;

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_filter($data, function($values) {
            return !$this->isEmpty($values[$this->filterKey] ?? null);
        });
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
