<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\console\controllers;


use yii\base\InvalidConfigException;
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
     * @param string $cache
     * @param string $tag
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionFlushTag(string $cache, string $tag): void
    {
        /** @var CacheInterface $cacheInstance */
        $cacheInstance = Instance::ensure($cache, CacheInterface::class);
        TagDependency::invalidate($cacheInstance, $tag);
    }

    /**
     * @param string $cache
     * @param string $key
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionGet(string $cache, string $key): void
    {
        /** @var CacheInterface $cacheInstance */
        $cacheInstance = Instance::ensure($cache, CacheInterface::class);
        $var = $cacheInstance->get($key);
        $this->stdout($var);
    }
}
