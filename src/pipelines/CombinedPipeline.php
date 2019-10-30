<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class CombinedPipeline
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CombinedPipeline extends BaseObject implements PipelineInterface
{
    /**
     * @var PipelineInterface[]
     */
    public $pipelines = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->pipelines as $key => $pipeline) {
            $this->pipelines[$key] = Instance::ensure($pipeline, PipelineInterface::class);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        foreach ($this->pipelines as $key => $pipeline) {
            if (isset($data[$key])) {
                $pipeline->process($data[$key]);
            }
        }
        return true;
    }
}
