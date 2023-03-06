<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use lujie\extend\caching\CachingTrait;
use yii\di\Instance;

/**
 * Class CachedDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CachedDataLoader extends BaseDataLoader
{
    use CachingTrait;

    /**
     * @var DataLoaderInterface
     */
    public $dataLoader;

    /**
     * @var string
     */
    public $cacheAllKey = 'ALL-WslxexTOryUNyv4G';

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'DataLoader:';

    /**
     * @var array
     */
    public $cacheTags = ['DataLoader'];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->dataLoader = Instance::ensure($this->dataLoader, DataLoaderInterface::class);
    }

    /**
     * @param int|string $key
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->getOrSet($key, function () use ($key) {
            return $this->dataLoader->get($key);
        });
    }

    /**
     * @param array $keys
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function multiGet(array $keys): array
    {
        $cacheKey = implode(';', $keys);
        return $this->getOrSet($cacheKey, function () use ($keys) {
            return parent::multiGet($keys);
        });
    }

    /**
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function all(): ?array
    {
        return $this->getOrSet($this->cacheAllKey, function () {
            return $this->dataLoader->all();
        });
    }
}
