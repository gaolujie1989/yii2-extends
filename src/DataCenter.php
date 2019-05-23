<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center;


use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\sources\BaseIncrementSource;
use lujie\data\exchange\sources\BatchSourceInterface;
use lujie\data\exchange\sources\ConditionSourceInterface;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\loader\ObjectedDataLoader;
use lujie\data\center\models\DataRecord;
use yii\base\InvalidArgumentException;
use yii\di\Instance;

/**
 * Class DataStation
 * @package lujie\data\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataCenter extends DataExchanger
{
    public $pipeline = [
        'class' => ActiveRecordPipeline::class,
        'modelClass' => DataRecord::class,
    ];

    /**
     * @var ObjectedDataLoader
     */
    public $sourceLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->sourceLoader = Instance::ensure($this->sourceLoader, DataLoaderInterface::class);
    }

    /**
     * @param $sourceId
     * @param array $condition
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function pullByCondition($sourceId, array $condition = []): bool
    {
        $source = $this->sourceLoader->get($sourceId);
        if (!($source instanceof ConditionSourceInterface) || !($source instanceof BatchSourceInterface)) {
            throw new InvalidArgumentException('Invalid source');
        }

        $source->setCondition($condition);
        foreach ($source->batch() as $items) {
            if (!$this->exchange($items)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $sourceId
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function pullIncrements($sourceId): bool
    {
        $source = $this->sourceLoader->get($sourceId);
        if (!($source instanceof BaseIncrementSource)) {
            throw new InvalidArgumentException('Invalid source');
        }

        foreach ($source->batch() as $items) {
            if (!$this->exchange($items)) {
                return false;
            }
            $source->ackReceived();
        }
        return true;
    }
}
