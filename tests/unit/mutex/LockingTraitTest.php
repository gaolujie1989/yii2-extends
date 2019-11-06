<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\caching;


use lujie\extend\tests\unit\mocks\MockMutexObject;
use Yii;
use yii\base\Exception;
use yii\mutex\Mutex;

class LockingTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

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
    public function testMe(): void
    {
        /** @var Mutex $mutex */
        $mutex = Yii::$app->get('mutex');
        $mutexObject = new MockMutexObject();

        $result = [];
        try {
            $mutexObject->lockingRun('aaa', static function () use (&$result) {
                $result['success'] = true;
                throw new Exception('success');
            }, static function () use (&$result) {
                $result['fail'] = true;
                throw new Exception('fail');
            });
        } catch (\Throwable $throwable) {
            $this->assertEquals('success', $throwable->getMessage());
        }
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayNotHasKey('fail', $result);
        $this->assertTrue($mutex->acquire('aaa'));

        $result = [];
        try {
            $mutexObject->lockingRun('aaa', static function () use (&$result) {
                $result['success'] = true;
                throw new Exception('success');
            }, static function () use (&$result) {
                $result['fail'] = true;
                throw new Exception('fail');
            });
        } catch (\Throwable $throwable) {
            $this->assertEquals('fail', $throwable->getMessage());
        }
        $this->assertArrayNotHasKey('success', $result);
        $this->assertArrayHasKey('fail', $result);
        $this->assertFalse($mutex->acquire('aaa'));
    }
}
