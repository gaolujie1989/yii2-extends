<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center;


use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\sources\BatchSourceInterface;
use lujie\data\exchange\sources\ConditionSourceInterface;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\di\Instance;

/**
 * Class DataStation
 * @package lujie\data\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataCenter extends BaseObject
{
    /**
     * @var ThirdPartSourceLoader
     */
    public $sourceLoader = [
        'class' => ThirdPartSourceLoader::class,
    ];

    /**
     * @var DataLoaderInterface
     */
    public $exchangerLoader = [

    ];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->sourceLoader = Instance::ensure($this->sourceLoader, DataLoaderInterface::class);
        $this->exchangerLoader = Instance::ensure($this->exchangerLoader, DataLoaderInterface::class);
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

        /** @var DataExchanger $exchanger */
        $exchanger = $this->exchangerLoader->get($sourceId);
        $source->setCondition($condition);
        foreach ($source->batch() as $items) {
            if (!$exchanger->exchange($items)) {
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
        if (!($source instanceof IncrementSource)) {
            throw new InvalidArgumentException('Invalid source');
        }

        /** @var DataExchanger $exchanger */
        $exchanger = $this->exchangerLoader->get($sourceId);
        foreach ($source->batch() as $items) {
            if (!$exchanger->exchange($items)) {
                return false;
            }
            $source->ackReceived();
        }
        return true;
    }
}
