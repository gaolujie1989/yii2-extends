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
class FillDefaultValueTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $defaultValues = [];

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map(function ($values) {
            $notEmptyValues = array_filter($values, [ValueHelper::class, 'notEmpty']);
            $fillValues = array_diff_key($this->defaultValues, $notEmptyValues);
            $values = array_merge($values, $fillValues);
            return $values;
        }, $data);
    }
}
