<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\caching;

use lujie\extend\helpers\ClassHelper;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\ChainedDependency;
use yii\caching\Dependency;
use yii\caching\TagDependency;
use yii\di\Instance;

/**
 * Trait CachingTrait
 *
 * @property int $cacheDuration = 3600;
 * @property string cacheKeyPrefix = '';
 * @property string[] cacheTags = [];
 * @property Dependency cacheDependency = null;
 *
 * @package lujie\extend\caching
 */
trait CachingTrait
{
    /**
     * @var CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var ?int
     */
    private $duration = 3600;

    /**
     * @var string
     */
    private $keyPrefix = '';

    /**
     * @var Dependency
     */
    private $dependency;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function initCache(): void
    {
        if ($this->initialized) {
            return;
        }

        if ($this->cache) {
            $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        }
        if (isset($this->cacheDuration) && is_int($this->cacheDuration)) {
            $this->duration = $this->cacheDuration;
        }
        if (isset($this->cacheKeyPrefix) && is_string($this->cacheKeyPrefix)) {
            $this->keyPrefix = $this->cacheKeyPrefix;
        } else {
            $this->keyPrefix = ClassHelper::getClassShortName($this) . ':';
        }
        if ($this->dependency === null) {
            if (isset($this->cacheDependency) && $this->cacheDependency instanceof Dependency) {
                $this->dependency = $this->cacheDependency;
            } elseif (isset($this->cacheTags) && $this->cacheTags) {
                $this->dependency = new TagDependency(['tags' => (array)$this->cacheTags]);
            }
        }
        $this->initialized = true;
    }

    /**
     * @param $key
     * @param $callable
     * @return mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getOrSet(string $key, $callable)
    {
        if ($this->cache) {
            $this->initCache();
            $key = $this->keyPrefix . $key;
            return $this->cache->getOrSet($key, $callable, $this->duration, $this->dependency);
        }
        return $callable();
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function flush(): void
    {
        $this->initCache();
        if ($this->dependency instanceof TagDependency) {
            TagDependency::invalidate($this->cache, $this->dependency->tags);
        } elseif ($this->dependency instanceof ChainedDependency) {
            foreach ($this->dependency->dependencies as $dependency) {
                if ($dependency instanceof TagDependency) {
                    TagDependency::invalidate($this->cache, $dependency->tags);
                }
            }
        }
    }

    /**
     * @param int $duration
     * @inheritdoc
     */
    public function setCacheDuration(int $duration = 3600): void
    {
        if (isset($this->cacheDuration)) {
            $this->cacheDuration = $duration;
        }
        $this->duration = $duration;
    }

    /**
     * @param string $keyPrefix
     * @inheritdoc
     */
    public function setCacheKeyPrefix(string $keyPrefix = ''): void
    {
        if (isset($this->cacheKeyPrefix)) {
            $this->cacheKeyPrefix = $keyPrefix;
        }
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param Dependency|null $dependency
     * @inheritdoc
     */
    public function setCacheDependency(?Dependency $dependency): void
    {
        if (isset($this->cacheDependency)) {
            $this->cacheDependency = $dependency;
        }
        $this->dependency = $dependency;
    }

    /**
     * @param array $tags
     * @inheritdoc
     */
    public function setCacheTags(array $tags = []): void
    {
        if (isset($this->cacheTags)) {
            $this->cacheTags = $tags;
        }
        $this->dependency = new TagDependency(['tags' => $tags]);
    }
}
