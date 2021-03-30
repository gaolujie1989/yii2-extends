<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\queue;

use lujie\extend\queue\RateLimitDelayBehavior;
use lujie\extend\tests\unit\mocks\MockJob;
use lujie\extend\tests\unit\mocks\MockRateLimitDelayJob;
use yii\queue\PushEvent;
use yii\queue\sync\Queue;

class RateLimitDelayBehaviorTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testJobRates(): void
    {
        $behavior = new RateLimitDelayBehavior();
        $behavior->jobRates = [
            'mock' => [
                MockJob::class => 1,
            ]
        ];
        $queue = new Queue();
        $queue->attachBehavior('rateLimit', $behavior);
        /** @var PushEvent[] $pushedJobEvents */
        $pushedJobEvents = [];
        $queue->on(Queue::EVENT_AFTER_PUSH, static function (PushEvent $event) use (&$pushedJobEvents) {
            $pushedJobEvents[] = $event;
        });

        $job = new MockJob();
        $queue->push($job);
        $queue->push($job);
        $queue->push($job);
        sleep(2);
        $queue->push($job);
        $queue->push($job);
        $this->assertCount(5, $pushedJobEvents);
        $this->assertEquals(0, $pushedJobEvents[0]->delay);
        $this->assertEquals(1, $pushedJobEvents[1]->delay);
        $this->assertEquals(2, $pushedJobEvents[2]->delay);
        $this->assertEquals(1, $pushedJobEvents[3]->delay);
        $this->assertEquals(2, $pushedJobEvents[4]->delay);
    }

    public function testRateLimitJob(): void
    {
        $behavior = new RateLimitDelayBehavior();
        $queue = new Queue();
        $queue->attachBehavior('rateLimit', $behavior);
        /** @var PushEvent[] $pushedJobEvents */
        $pushedJobEvents = [];
        $queue->on(Queue::EVENT_AFTER_PUSH, static function (PushEvent $event) use (&$pushedJobEvents) {
            $pushedJobEvents[] = $event;
        });

        $job = new MockRateLimitDelayJob(['accountId' => 1]);
        $queue->push($job);
        $queue->push($job);
        $queue->push($job);
        $job = new MockRateLimitDelayJob(['accountId' => 2]);
        $queue->push($job);
        $queue->push($job);
        $this->assertCount(5, $pushedJobEvents);
        $this->assertEquals(0, $pushedJobEvents[0]->delay);
        $this->assertEquals(2, $pushedJobEvents[1]->delay);
        $this->assertEquals(4, $pushedJobEvents[2]->delay);
        $this->assertEquals(0, $pushedJobEvents[3]->delay);
        $this->assertEquals(2, $pushedJobEvents[4]->delay);
    }
}
