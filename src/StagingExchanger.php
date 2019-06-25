<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;


use lujie\data\exchange\DataExchange;
use lujie\data\exchange\Exchanger;
use lujie\data\exchange\sources\BatchSourceInterface;
use lujie\data\exchange\sources\ConditionSourceInterface;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\di\Instance;

/**
 * Class DataStaging
 * @package lujie\data\staging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StagingExchanger extends Exchanger
{
    /**
     * @var DataLoaderInterface|StagingExchangeLoader
     */
    public $exchangerLoader = StagingExchangeLoader::class;

    /**
     * @param $sourceId
     * @param array $condition
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function executeCondition($sourceId, array $condition = []): bool
    {
        $dataExchange = $this->exchangerLoader->get($sourceId);
        if ($dataExchange->source instanceof ConditionSourceInterface) {
            $dataExchange->source->setCondition($condition);
        } else {
            throw new InvalidCallException('Source not implements ConditionSourceInterface');
        }
        if ($dataExchange->source instanceof IncrementSource) {
            $dataExchange->source = $dataExchange->source->source;
        }
        return $dataExchange->execute();
    }
}
