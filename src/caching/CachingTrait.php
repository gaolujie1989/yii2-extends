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
 * @property string $cacheKeyPrefix = '';
 * @property string[] $cacheTags = [];
 * @property Dependency $cacheDependency = null;
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
     * @param string $key
     * @return mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getCacheValue(string $key)
    {
        if ($this->cache) {
            $this->initCache();
            $key = $this->keyPrefix . $key;
            return $this->cache->get($key);
        }
        throw new InvalidConfigException('Cache not set');
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null|bool $duration
     * @param Dependency|null|bool $dependency
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function setCacheValue(string $key, $value, $duration = true, $dependency = true)
    {
        if ($this->cache) {
            $this->initCache();
            $key = $this->keyPrefix . $key;
            $duration = $duration === true ? $this->duration : $duration;
            $dependency = $dependency === true ? $this->dependency : $dependency;
            return $this->cache->set($key, $value, $duration, $dependency);
        }
        throw new InvalidConfigException('Cache not set');
    }

    /**
     * @param string $key
     * @param array|callable $callable
     * @param int|null|bool $duration
     * @param Dependency|null|bool $dependency
     * @return mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getOrSetCacheValue(string $key, $callable, $duration = true, $dependency = true)
    {
        if ($this->cache) {
            $this->initCache();
            $key = $this->keyPrefix . $key;
            $duration = $duration === true ? $this->duration : $duration;
            $dependency = $dependency === true ? $this->dependency : $dependency;
            return $this->cache->getOrSet($key, $callable, $duration, $dependency);
        }
        return $callable();
    }

    /**
     * @param string $key
     * @param array|callable $callable
     * @param int|null|bool $duration
     * @param Dependency|null|bool $dependency
     * @return mixed
     * @throws InvalidConfigException
     * @deprecated
     * @inheritdoc
     */
    public function getOrSet(string $key, $callable, $duration = true, $dependency = true)
    {
        return $this->getOrSetCacheValue($key, $callable, $duration, $dependency);
    }

    /**
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
     * @param ?Dependency $dependency
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
