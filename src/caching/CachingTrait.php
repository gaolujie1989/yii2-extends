<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\caching;

use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\ChainedDependency;
use yii\caching\Dependency;
use yii\caching\TagDependency;
use yii\di\Instance;

/**
 * Trait CachingTrait
 * @package lujie\extend\caching
 */
trait CachingTrait
{
    /**
     * @var CacheInterface
     */
    public $cache;

    /**
     * @var int
     */
    public $cacheDuration;

    /**
     * @var Dependency
     */
    public $cacheDependency;

    /**
     * @var string
     */
    public $cacheKeyPrefix;

    /**
     * @var string[]
     */
    public $cacheTags;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initCache();
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function initCache()
    {
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        if (empty($this->cacheKeyPrefix)) {
            throw new InvalidConfigException('Cache key prefix must be set.');
        }
        if ($this->cacheTags) {
            $tagDependency = new TagDependency(['tags' => (array)$this->cacheTags]);
            if ($this->cacheDependency) {
                $this->cacheDependency = new ChainedDependency([
                    'dependencies' => [$this->cacheDependency, $tagDependency]
                ]);
            } else {
                $this->cacheDependency = $tagDependency;
            }
        }
    }

    /**
     * @param $key
     * @param $callable
     * @return mixed
     * @inheritdoc
     */
    public function getOrSet($key, $callable)
    {
        return $this->cache->getOrSet($key, $callable, $this->cacheDuration, $this->cacheDependency);
    }
}
