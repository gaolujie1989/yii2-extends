<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\caching;

use dpd\ParcelLifeCycleServiceClassmap;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class ChainedCache
 * @package lujie\extend\caching
 * @author Lujie Zhou <gao_lujie@live.cn>
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

    /**
     * @param string $key
     * @return false|mixed|null
     * @inheritdoc
     */
    protected function getValue($key)
    {
        foreach ($this->caches as $cache) {
            $value = $cache->get($key);
            if ($value) {
                return $value;
            }
        }
        return null;
    }

    protected function setValue($key, $value, $duration)
    {

    }

    protected function addValue($key, $value, $duration)
    {

    }

    protected function deleteValue($key)
    {

    }

    protected function flushValues()
    {

    }
}
