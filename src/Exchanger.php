<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\sources\ConditionSourceInterface;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\loader\DataLoaderInterface;
use lujie\executing\Executor;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;

/**
 * Class ExchangeManager
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Exchanger extends Executor
{
    /**
     * @var DataLoaderInterface
     */
    public $exchangeLoader;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->exchangeLoader = Instance::ensure($this->exchangeLoader, DataLoaderInterface::class);
    }

    /**
     * @param $key
     * @return DataExchanger|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getExchange($key): DataExchanger
    {
        $config = $this->exchangeLoader->get($key);
        if (empty($config)) {
            throw new InvalidArgumentException("Exchange {$key} not found.");
        }
        return Instance::ensure($config, DataExchanger::class);
    }

    /**
     * @param $key
     * @param array $condition
     * @return bool
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function executeCondition($key, array $condition = []): bool
    {
        $dataExchange = $this->getExchange($key);
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
