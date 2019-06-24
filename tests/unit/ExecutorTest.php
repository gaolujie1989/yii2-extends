<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\tests\unit;

use lujie\executing\Executor;
use lujie\executing\tests\unit\mocks\TestExecutable;
use Yii;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class ExecutorTest
 * @package lujie\executing\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutorTest extends \Codeception\Test\Unit
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

    public function testExecute(): void
    {
        $executor = new Executor();
        $executable = new TestExecutable();

        Yii::$app->params['xxx'] = null;
        $this->assertTrue($executor->execute($executable));
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }

    public function testExecuteLockable(): void
    {
        $executor = new Executor();
        $executable = new TestExecutable();
        $executable->id = 1;
        $executable->shouldLocked = true;

        Yii::$app->params['xxx'] = null;
        $this->assertTrue($executor->execute($executable));
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $executable->shouldLocked = true;
        Yii::$app->params['xxx'] = null;
        $this->assertFalse($executor->execute($executable));
        $this->assertNull(Yii::$app->params['xxx']);

        $executable->shouldLocked = true;
        $executable->id = 2;
        Yii::$app->params['xxx'] = null;
        $this->assertTrue($executor->execute($executable));
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }

    public function testHandle(): void
    {
        $executor = new Executor();
        $executable = new TestExecutable();

        Yii::$app->params['xxx'] = null;
        $this->assertTrue($executor->handle($executable));
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }

    public function testHandleQueueable(): void
    {
        /** @var Queue $queue */
        $queue = Instance::ensure('queue', Queue::class);
        $executor = new Executor();
        Yii::$app->set('executor', $executor);
        Yii::$app->get('executor', $executor);
        $executable = new TestExecutable();
        $executable->shouldQueued = true;
        $executable->ttr = 300;
        $executable->attempts = 1;

        Yii::$app->params['xxx'] = null;
        $jobId = $executor->handle($executable);
        $this->assertTrue($jobId > 1);
        $this->assertNull(Yii::$app->params['xxx']);
        $this->assertTrue($queue->isWaiting($jobId));
    }
}
