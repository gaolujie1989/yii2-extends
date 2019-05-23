<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\authclient;

use Yii;
use yii\authclient\StateStorageInterface;
use yii\base\Component;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class CacheStateStorage
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CacheStateStorage extends Component implements StateStorageInterface
{
    /**
     * @var Cache
     */
    public $cache;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->cache === null) {
            if (Yii::$app->has('cache')) {
                $this->cache = Yii::$app->get('cache');
            }
        } else {
            $this->cache = Instance::ensure($this->cache, Cache::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if ($this->cache !== null) {
            $this->cache->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->cache !== null) {
            return $this->cache->get($key);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if ($this->cache !== null) {
            $this->cache->delete($key);
        }
        return true;
    }
}
