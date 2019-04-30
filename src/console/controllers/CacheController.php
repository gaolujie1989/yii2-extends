<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\console\controllers;


use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;

/**
 * Class CacheController
 * @package lujie\extend\console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CacheController extends \yii\console\controllers\CacheController
{
    /**
     * @param $cache
     * @param $tag
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionFlushTag($cache, $tag)
    {
        /** @var CacheInterface $cache */
        $cache = Instance::ensure($cache, CacheInterface::class);
        TagDependency::invalidate($cache, $tag);
    }

    /**
     * @param $cache
     * @param $key
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionGet($cache, $key)
    {
        /** @var CacheInterface $cache */
        $cache = Instance::ensure($cache, CacheInterface::class);
        $var = $cache->get($key);
        $this->stdout($var);
    }
}
