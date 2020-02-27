<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tasks;

use yii\base\BaseObject;
use yii\caching\CacheInterface;
use yii\di\Instance;

/**
 * Class HeatBeatTask
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HeartBeatTask extends BaseObject
{
    /**
     * @var CacheInterface|mixed
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cacheKey = 'HeatBeat';

    /**
     * @var int
     */
    public $sleep = 0;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        $value = 'HeatBeatTask:' . date('Y-m-d H:i:s');
        $this->cache->set($this->cacheKey, $value, 3600);
        if ($this->sleep) {
            sleep($this->sleep);
        }
    }
}
