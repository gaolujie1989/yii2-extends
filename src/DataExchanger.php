<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;


use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\exchange\transformers\TransformerInterface;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class Exchanger
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataExchanger extends BaseObject implements DataExchangerInterface
{
    /**
     * @var TransformerInterface
     */
    public $transformer;

    /**
     * @var PipelineInterface
     */
    public $pipeline;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->transformer) {
            $this->transformer = Instance::ensure($this->transformer, TransformerInterface::class);
        }
        $this->pipeline = Instance::ensure($this->pipeline, PipelineInterface::class);
    }

    /**
     * @param SourceInterface $source
     * @return bool
     * @inheritdoc
     */
    public function exchange(SourceInterface $source): bool
    {
        $data = $source->all();
        if ($this->transformer) {
            $data = $this->transformer->transform($data);
        }
        if (empty($data)) {
            return false;
        }

        return $this->pipeline->process($data);
    }
}
