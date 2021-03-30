<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\tests\unit;

use lujie\executing\ExecutableJob;
use lujie\executing\Executor;
use lujie\executing\tests\unit\mocks\TestExecutable;
use Yii;

/**
 * Class ExecutorTest
 * @package lujie\executing\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableJobTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testMe(): void
    {
        $executor = new Executor();
        Yii::$app->set('executor', $executor);
        Yii::$app->get('executor', $executor);

        $job = new ExecutableJob([
            'executor' => 'executor',
            'executable' => new TestExecutable(),
        ]);
        Yii::$app->params['xxx'] = null;
        $job->execute(null);
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }
}
