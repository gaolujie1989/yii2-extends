<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log;

use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\log\EmailTarget;
use yii\log\FileTarget;
use yii\log\Target;

/**
 * Class LogTargetsAdjuster
 * @package lujie\extend\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LogTargetAdjuster extends BaseObject implements BootstrapInterface
{
    public $targets = [
        'appErrorEmail' => [
            'class' => EmailTarget::class,
            'mailer' => 'mailer',
            'message' => [
                'to' => ['lujie.zhou@skylinktools.com'],
                'subject' => 'Log message',
            ],
            'levels' => ['error'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appErrorFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/app-error.log',
            'levels' => ['error'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appWarningFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/app-warning.log',
            'levels' => ['warning'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appInfoFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/app-info.log',
            'levels' => ['info'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appDebugFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/app-trace.log',
            'levels' => ['trace'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'yiiErrorEmail' => [
            'class' => EmailTarget::class,
            'mailer' => 'mailer',
            'message' => [
                'to' => ['lujie.zhou@skylinktools.com'],
                'subject' => 'Log message',
            ],
            'levels' => ['error'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
        'yiiErrorFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-error.log',
            'levels' => ['error'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
        'yiiWarningFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-warning.log',
            'levels' => ['warning'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
        'yiiInfoFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-info.log',
            'levels' => ['info'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
        'yiiDebugFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-trace.log',
            'levels' => ['trace'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
    ];

    /**
     * @var array
     */
    public $scenarioTargets = [
        'prod' => [
            'appErrorEmail',
            'appErrorFile',
            'appWarningFile',
            'yiiErrorEmail',
            'yiiErrorFile',
            'yiiWarningFile',
        ],
        'info' => [
            'appErrorEmail',
            'appErrorFile',
            'appWarningFile',
            'yiiErrorEmail',
            'yiiErrorFile',
            'yiiWarningFile',
        ],
        'debug' => [
            'appErrorEmail',
            'appErrorFile',
            'appWarningFile',
            'appDebugFile',
            'yiiErrorEmail',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiDebugFile',
        ],
        'test' => [
            'appErrorFile',
            'appWarningFile',
            'appInfoFile',
            'appDebugFile',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiInfoFile',
            'yiiDebugFile',
        ],
        'dev' => [
            'appErrorFile',
            'appWarningFile',
            'appInfoFile',
            'appDebugFile',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiInfoFile',
            'yiiDebugFile',
        ],
    ];

    /**
     * @var string
     */
    public $scenarioKey = 'log';

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'updateLogTargets']);
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function updateLogTargets(): void
    {
        if ($targets = $this->getScenarioTargets()) {
            Yii::debug('Set log targets: ' . implode(',', array_keys($targets)), __METHOD__);
            Yii::$app->getLog()->targets = $targets;
        }
    }

    /**
     * @return array|null|Target[]
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getScenarioTargets(): ?array
    {
        $scenario = $this->getLogScenario();
        if (empty($this->scenarioTargets[$scenario])) {
            Yii::debug("Invalid log scenario {$scenario}.");
            return null;
        }
        $scenarioTargets = [];
        foreach ($this->scenarioTargets[$scenario] as $name) {
            if (!($this->targets[$name] instanceof Target)) {
                $this->targets[$name] = Yii::createObject($this->targets[$name]);
            }
            $scenarioTargets[$name] = $this->targets[$name];
        }
        return $scenarioTargets;
    }

    /**
     * @return string|null
     * @inheritdoc
     */
    protected function getLogScenario(): ?string
    {
        if (empty($this->scenarioKey)) {
            return YII_ENV;
        }
        return $_REQUEST[$this->scenarioKey] ?? $_ENV[$this->scenarioKey] ?? YII_ENV;
    }
}
