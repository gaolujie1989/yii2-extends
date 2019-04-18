<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;


use yii\base\BaseObject;
use yii\caching\CacheInterface;
use yii\di\Instance;

/**
 * Class HeatBeatTask
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HeatBeatTask extends BaseObject
{
    /**
     * @var CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var
     */
    public $cacheKey = 'HeatBeat';

    public function execute()
    {
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        $value = 'HeatBeatTask:' . date('Y-m-d H:i:s');
        $this->cache->set($this->cacheKey, $value, 3600);
    }
}
