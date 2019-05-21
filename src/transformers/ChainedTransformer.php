<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;


use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class ChainedTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChainedTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var TransformerInterface[]
     */
    public $transformers = [];

    /**
     * @param array $data
     * @return array|void
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function transform(array $data)
    {
        foreach ($this->transformers as $transformer) {
            if (is_callable($transformer)) {
                $data = $transformer($data);
            } else {
                $transformer = Instance::ensure($transformer, TransformerInterface::class);
                $data = $transformer->transform($data);
            }
        }
        return $data;
    }
}
