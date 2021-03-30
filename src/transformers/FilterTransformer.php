<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use lujie\extend\helpers\ValueHelper;
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
        return array_filter($data, function ($values) {
            return !ValueHelper::isEmpty($values[$this->filterKey] ?? null);
        });
    }
}
