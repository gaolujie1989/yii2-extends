<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\log;

use lujie\extend\log\LogTargetAdjuster;
use Yii;
use yii\log\FileTarget;

class LogTargetAdjusterTest extends \Codeception\Test\Unit
{
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
        $_ENV['log'] = 'debug';
        $adjuster = new LogTargetAdjuster([
            'targets' => [
                'debugFile' => [
                    'class' => FileTarget::class,
                    'logFile' => '@runtime/logs/debug.log',
                    'levels' => ['error'],
                    'logVars' => [],
                    'categories' => ['debug'],
                ],
            ],
            'scenarioTargets' => [
                'debug' => [
                    'debugFile' => 'debugFile',
                    'appDebugFile' => null,
                    'yiiDebugFile' => null,
                ]
            ],
        ]);
        $adjuster->updateLogTargets();
        $expected = [
            'appErrorFile',
            'appWarningFile',
            'appProfileFile',
            'appInfoFile',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiHttpInfoFile',
            'debugFile',
            'appDebugFile',
            'yiiInfoFile',
            'yiiDbInfoFile',
            'yiiProfileFile',
            'yiiDebugFile',
        ];
        $targetNames = array_keys(Yii::$app->getLog()->targets);
        $this->assertEmpty(array_diff($expected, $targetNames));
    }
}
