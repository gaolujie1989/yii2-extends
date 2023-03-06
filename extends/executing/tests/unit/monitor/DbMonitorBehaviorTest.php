<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\tests\unit\monitor;

use lujie\executing\Executor;
use lujie\executing\monitor\behaviors\DbMonitorBehavior;
use Yii;

class DbMonitorBehaviorTest extends ActiveRecordMonitorBehaviorTest
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return Executor
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getExecutor(): Executor
    {
        $executor = new Executor();
        $executor->attachBehavior('monitor', [
            'class' => DbMonitorBehavior::class
        ]);
        Yii::$app->set('executor', $executor);
        Yii::$app->get('executor', $executor);
        return $executor;
    }
}
