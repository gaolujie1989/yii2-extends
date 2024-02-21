<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tasks;

use Generator;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\scheduling\CronTask;
use yii\caching\CacheInterface;
use yii\di\Instance;

/**
 * Class HeatBeatTask
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HeartBeatTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cacheKey = 'HeatBeat';

    /**
     * @var int
     */
    public $sleep = 1;

    public $loops = 300;

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        return 60;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return ['cache', 'cacheKey', 'sleep'];
    }

    /**
     * @return Generator
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): Generator
    {
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        $loops = $this->loops;
        $progress = $this->getProgress($this->loops);
        while($loops-- > 0) {
            $value = 'HeatBeatTask:' . date('Y-m-d H:i:s');
            $this->cache->set($this->cacheKey, $value, 3600);
            if ($this->sleep) {
                sleep($this->sleep);
            }
            $progress->done++;
            yield $value;
        }
    }
}
