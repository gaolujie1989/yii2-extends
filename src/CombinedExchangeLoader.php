<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\staging\models\DataSource;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;

/**
 * Class CombinedExchangeLoader
 * @package lujie\data\staging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CombinedExchangeLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var array
     */
    public $stagingExchangeLoaders = [];

    /**
     * @param int|string $key
     * @return mixed|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        $dataSource = DataSource::findOne($key);
        if ($dataSource === null) {
            return null;
        }

        $exchangeLoader = $this->getStagingExchangeLoader($dataSource->dataAccount->type);
        return $exchangeLoader->get($key);
    }

    /**
     * @param string $accountType
     * @return DataLoaderInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getStagingExchangeLoader(string $accountType): DataLoaderInterface
    {
        if (empty($this->stagingExchangeLoaders[$accountType])) {
            throw new InvalidConfigException('ExchangeLoader not config');
        }

        $this->stagingExchangeLoaders[$accountType] = Instance::ensure($this->stagingExchangeLoaders[$accountType], DataLoaderInterface::class);
        return $this->stagingExchangeLoaders[$accountType];
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('not support');
    }
}
