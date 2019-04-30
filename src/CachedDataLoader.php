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
     * @return array|void|null
     * @inheritdoc
     */
    public function get($key)
    {
        $cacheKey = $this->cacheKeyPrefix . $key;
        $this->getOrSet($cacheKey, function() use ($key) {
            return $this->dataLoader->get($key);
        });
    }

    public function all()
    {
        $cacheKey = $this->cacheKeyPrefix . $this->cacheAllKey;
        $this->getOrSet($cacheKey, function() {
            return $this->dataLoader->all();
        });
    }
}
