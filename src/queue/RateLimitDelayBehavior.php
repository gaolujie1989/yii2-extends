<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

use lujie\extend\caching\CachingTrait;
use Yii;
use yii\base\Behavior;
use yii\queue\JobInterface;
use yii\queue\PushEvent;
use yii\queue\Queue;

/**
 * Class RateLimitDelayBehavior
 * @package lujie\extend\queue
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RateLimitDelayBehavior extends Behavior
{
    use CachingTrait;

    /**
     * [
     *      'jobClasses' => [],
     *      'delay' => 1,
     * ]
     * @var array
     */
    public $jobRates = [];

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'RateLimitDelayBehavior:';

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Queue::EVENT_BEFORE_PUSH => 'beforePush'
        ];
    }

    /**
     * @param PushEvent $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function beforePush(PushEvent $event): void
    {
        if ($event->delay > 0) {
            Yii::debug('Custom delay, skip rate limit', __METHOD__);
            return;
        }
        foreach ($this->jobRates as $key => $jobRate) {
            if ($this->isRateLimitedJob($event->job, $jobRate['jobClasses'] ?? [])) {
                $event->delay = $this->getDelay($key);
                Yii::info("Rate limit delay {$event->delay} of limit {$key}", __METHOD__);
            }
        }
    }

    /**
     * @param JobInterface $job
     * @param array $jobClasses
     * @return bool
     * @inheritdoc
     */
    protected function isRateLimitedJob(JobInterface $job, array $jobClasses): bool
    {
        foreach ($jobClasses as $jobClass) {
            if ($job instanceof $jobClass) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $key
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getDelay($key): int
    {
        $cacheKey = 'delay:' . $key;
        [$time, $delay] = $this->getOrSet($cacheKey, static function () {
            return [time(), -999];
        });

        $now = time();
        $limitDelay = $this->jobRates[$key]['delay'] ?? 1;
        $delay = $delay - ($now - $time) + $limitDelay;
        if ($delay < 0) {
            $delay = 0;
        }
        $time = $now;
        $this->cache->set($this->cacheKeyPrefix . $cacheKey, [$time, $delay]);
        return $delay;
    }
}
