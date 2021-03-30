<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\console\controllers;

use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\helpers\VarDumper;

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
    public function actionFlushTag(string $tag, string $cache = 'cache'): void
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
    public function actionGet(string $key, string $cache = 'cache'): void
    {
        /** @var CacheInterface $cacheInstance */
        $cacheInstance = Instance::ensure($cache, CacheInterface::class);
        $var = $cacheInstance->get($key);
        $this->stdout(VarDumper::dumpAsString($var));
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $cache
     * @param int|null $duration
     * @param string|null $tags
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionSet(string $key, string $value, string $cache = 'cache', ?int $duration = null, ?string $tags = null): void
    {
        /** @var CacheInterface $cacheInstance */
        $cacheInstance = Instance::ensure($cache, CacheInterface::class);
        if ($tags) {
            $tagDependency = new TagDependency(['tags' => explode(',', $tags)]);
        }
        $cacheInstance->set($key, $value, $duration, $tagDependency ?? null);
    }

    /**
     * @param string $key
     * @param string $cache
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionDelete(string $key, string $cache = 'cache'): void
    {
        /** @var CacheInterface $cacheInstance */
        $cacheInstance = Instance::ensure($cache, CacheInterface::class);
        $cacheInstance->delete($key);
    }
}
