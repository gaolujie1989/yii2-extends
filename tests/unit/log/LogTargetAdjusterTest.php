<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\log;

use lujie\extend\log\LogTargetAdjuster;
use Yii;

class LogTargetAdjusterTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testMe(): void
    {
        $adjuster = new LogTargetAdjuster();
        $adjuster->updateLogTargets();
        $expected = [
            'appErrorFile',
            'appWarningFile',
            'appInfoFile',
            'appDebugFile',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiInfoFile',
            'yiiDebugFile',
        ];
        $targetNames = array_keys(Yii::$app->getLog()->targets);
        $this->assertEquals($expected, $targetNames);
    }
}
