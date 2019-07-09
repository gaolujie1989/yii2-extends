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
            return array_merge($this->defaultValues, $values);
        }, $data);
    }
}
