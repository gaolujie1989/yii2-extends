<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use lujie\extend\caching\CachingTrait;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class CachedDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CachedDataLoader extends BaseObject implements DataLoaderInterface
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
    public $cacheKeyPrefix = 'dataLoader:';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataLoader = Instance::ensure($this->dataLoader, DataLoaderInterface::class);
        $this->initCache();
    }

    /**
     * @param int|string $key
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        $cacheKey = $this->cacheKeyPrefix . $key;
        return $this->getOrSet($cacheKey, function() use ($key) {
            return $this->dataLoader->get($key);
        });
    }

    /**
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function all(): ?array
    {
        $cacheKey = $this->cacheKeyPrefix . $this->cacheAllKey;
        return $this->getOrSet($cacheKey, function() {
            return $this->dataLoader->all();
        });
    }
}
