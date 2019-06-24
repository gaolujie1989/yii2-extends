<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\sources\SourceInterface;
use lujie\data\loader\DataLoaderInterface;
use lujie\executing\Executor;
use yii\base\Component;
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->exchangeLoader = Instance::ensure($this->exchangeLoader, DataLoaderInterface::class);
    }

    /**
     * @param int|string $name
     * @return DataExchange|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getExchange($name): DataExchange
    {
        $config = $this->exchangeLoader->get($name);
        return Instance::ensure($config, DataExchange::class);
    }
}
