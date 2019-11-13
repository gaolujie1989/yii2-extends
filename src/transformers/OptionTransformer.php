<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;

/**
 * Class KeyMappedTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @param array $data
     * @return array|null
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map(function($values) {
            foreach ($this->options as $key => $options) {
                if (isset($values[$key], $options[$values[$key]])) {
                    $values[$key] = $options[$values[$key]];
                }
            }
            return $values;
        }, $data);
    }
}
