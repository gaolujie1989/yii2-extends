<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\base\BaseObject;

/**
 * Class ArrayPipeline
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayPipeline extends BaseObject implements PipelineInterface
{
    public $data = [];

    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $this->data = array_merge($this->data, $data);
        return true;
    }
}
