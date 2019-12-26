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
     *      'xxx' => [
     *          'xxxJobClass' => 1
     *      ]
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
        foreach ($this->jobRates as $key => $jobDelays) {
            if ($delay = $this->getJoaRateDelay($event->job, $jobDelays)) {
                $event->delay = $this->getDelay($key, $delay);
                Yii::info("Rate limit delay {$event->delay} of limit {$key}", __METHOD__);
            }
        }
    }

    /**
     * @param JobInterface $job
     * @param array $jobDelays
     * @return int|null
     * @inheritdoc
     */
    protected function getJoaRateDelay(JobInterface $job, array $jobDelays): ?int
    {
        foreach ($jobDelays as $jobClass => $delay) {
            if ($job instanceof $jobClass) {
                return $delay;
            }
        }
        return null;
    }

    /**
     * @param string $key
     * @param int $rateDelay
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getDelay(string $key, int $rateDelay): int
    {
        $cacheKey = 'delay:' . $key;
        [$time, $delay] = $this->getOrSet($cacheKey, static function () {
            return [time(), -999];
        });

        $now = time();
        $delay = $delay - ($now - $time) + $rateDelay;
        if ($delay < 0) {
            $delay = 0;
        }
        $time = $now;
        $this->cache->set($this->cacheKeyPrefix . $cacheKey, [$time, $delay]);
        return $delay;
    }
}
