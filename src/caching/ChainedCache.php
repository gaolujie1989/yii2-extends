<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\caching;

use yii\base\NotSupportedException;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class ChainedCache
 * @package lujie\extend\caching
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated will cause bug
 */
class ChainedCache extends Cache
{
    /**
     * @var Cache[]
     */
    public $caches = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->caches as $key => $cache) {
            $this->caches[$key] = Instance::ensure($cache, Cache::class);
        }
    }

    #region overrides

    /**
     * @param mixed $key
     * @return bool|mixed
     * @inheritdoc
     */
    public function get($key)
    {
        foreach ($this->caches as $cache) {
            $value = $cache->get($key);
            if ($value !== false && $value !== null) {
                return $value;
            }
        }
        return false;
    }

    /**
     * @param mixed $key
     * @return bool
     * @inheritdoc
     */
    public function exists($key): bool
    {
        foreach ($this->caches as $cache) {
            if ($cache->exists($key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string[] $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet($keys): array
    {
        $results = [];
        $cacheKeys = $keys;
        foreach ($this->caches as $cache) {
            $cacheResults = $cache->multiGet($cacheKeys);
            $cacheKeys = [];
            foreach ($cacheResults as $key => $value) {
                if ($value === false || $value === null) {
                    $cacheKeys[] = $key;
                } else {
                    $results[$key] = $value;
                }
            }
            if (empty($cacheKeys)) {
                return $results;
            }
        }
        return $results;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param null $duration
     * @param null $dependency
     * @return bool
     * @inheritdoc
     */
    public function set($key, $value, $duration = null, $dependency = null): bool
    {
        foreach ($this->caches as $cache) {
            if ($cache->set($key, $value, $duration, $dependency) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $items
     * @param int $duration
     * @param null $dependency
     * @return array
     * @inheritdoc
     */
    public function multiSet($items, $duration = 0, $dependency = null): array
    {
        foreach ($this->caches as $cache) {
            if ($failedKeys = $cache->multiSet($items, $duration, $dependency)) {
                return $failedKeys;
            }
        }
        return [];
    }


    /**
     * @param array $items
     * @param int $duration
     * @param null $dependency
     * @return array
     * @inheritdoc
     */
    public function multiAdd($items, $duration = 0, $dependency = null): array
    {
        foreach ($this->caches as $cache) {
            if ($failedKeys = $cache->multiAdd($items, $duration, $dependency)) {
                return $failedKeys;
            }
        }
        return [];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param int $duration
     * @param null $dependency
     * @return bool
     * @inheritdoc
     */
    public function add($key, $value, $duration = 0, $dependency = null): bool
    {
        foreach ($this->caches as $cache) {
            if ($cache->add($key, $value, $duration, $dependency) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed $key
     * @return bool
     * @inheritdoc
     */
    public function delete($key): bool
    {
        foreach ($this->caches as $cache) {
            if ($cache->delete($key) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function flush(): bool
    {
        foreach ($this->caches as $cache) {
            if ($cache->flush() === false) {
                return false;
            }
        }
        return true;
    }

    #endregion

    #region implements

    /**
     * @param string $key
     * @return false|mixed|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function getValue($key)
    {
        throw new NotSupportedException('Method not supported');
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return bool|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function setValue($key, $value, $duration)
    {
        throw new NotSupportedException('Method not supported');
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return bool|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function addValue($key, $value, $duration)
    {
        throw new NotSupportedException('Method not supported');
    }

    /**
     * @param string $key
     * @return bool|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function deleteValue($key)
    {
        throw new NotSupportedException('Method not supported');
    }

    /**
     * @return bool|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function flushValues()
    {
        throw new NotSupportedException('Method not supported');
    }

    #endregion
}
