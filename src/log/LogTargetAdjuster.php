<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log;

use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\log\DbTarget;
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
    /**
     * @var array[]
     */
    public $defaultTargets = [
        'appErrorEmail' => [
            'class' => EmailTarget::class,
            'levels' => ['error'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],

        'appErrorDb' => [
            'class' => DbTarget::class,
            'levels' => ['error'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appWarningDb' => [
            'class' => DbTarget::class,
            'levels' => ['warning'],
            'logVars' => [],
            'except' => ['yii\*'],
        ],
        'appProfileDb' => [
            'class' => DbTarget::class,
            'levels' => ['profile'],
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
        'appProfileFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/app-profile.log',
            'levels' => ['profile'],
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
            'levels' => ['error'],
            'logVars' => [],
            'categories' => ['yii\*'],
            'except' => ['yii\web\HttpException:4*', 'yii\console\UnknownCommandException*'], //4xx httpCode not need send mail
        ],

        'yiiErrorDb' => [
            'class' => DbTarget::class,
            'levels' => ['error'],
            'logVars' => [],
            'categories' => ['yii\*'],
            'except' => ['yii\web\HttpException:4*', 'yii\console\UnknownCommandException*'], //4xx httpCode not need send mail
        ],
        'yiiWarningDb' => [
            'class' => DbTarget::class,
            'levels' => ['warning'],
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
        'yiiProfileFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-profile.log',
            'levels' => ['profile'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],
        'yiiInfoFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-info.log',
            'levels' => ['info'],
            'logVars' => [],
            'categories' => ['yii\*'],
            'except' => ['yii\db\*', 'yii\httpclient\*'],
        ],
        'yiiDbInfoFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-db-info.log',
            'levels' => ['info'],
            'logVars' => [],
            'categories' => ['yii\db\*'],
        ],
        'yiiHttpInfoFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-http-info.log',
            'levels' => ['info'],
            'logVars' => [],
            'categories' => ['yii\httpclient\*'],
        ],
        'yiiDebugFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/yii-trace.log',
            'levels' => ['trace'],
            'logVars' => [],
            'categories' => ['yii\*'],
        ],

        'debugFile' => [
            'class' => FileTarget::class,
            'logFile' => '@runtime/logs/debug.log',
            'levels' => ['trace'],
            'logVars' => [],
            'categories' => ['debug'],
        ]
    ];

    /**
     * @var array
     */
    public $defaultScenarioTargets = [
        'default' => [
            'appErrorFile',
            'appWarningFile',
            'appProfileFile',
            'appInfoFile',
            'yiiErrorFile',
            'yiiWarningFile',
            'yiiHttpInfoFile',
            'debugFile',
        ],
        'prod' => [
            'appErrorEmail',
            'yiiErrorEmail',
        ],
        'test' => [
            'yiiInfoFile',
            'yiiDbInfoFile',
        ],
        'dev' => [
        ],
        'debug' => [
            'appDebugFile',
            'yiiInfoFile',
            'yiiDbInfoFile',
            'yiiProfileFile',
            'yiiDebugFile',
        ],
    ];

    /**
     * @var array[]
     */
    public $targets = [];

    /**
     * @var array
     */
    public $scenarioTargets = [];

    /**
     * @var string
     */
    public $scenarioKey = 'log';

    /**
     * @var array
     */
    public $emailTargetConfig = [];

    /**
     * @var string[]
     */
    public $remainTargets = ['debug'];

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'updateLogTargets']);
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->defaultScenarioTargets as $scenario => $scenarioTargets) {
            $this->defaultScenarioTargets[$scenario] = array_combine($scenarioTargets, $scenarioTargets);
        }
        foreach ($this->defaultTargets as $key => $target) {
            if ($target['class'] === EmailTarget::class) {
                $this->defaultTargets[$key] = array_merge($target, $this->emailTargetConfig);
            }
        }
        foreach ($this->scenarioTargets as $scenario => $scenarioTargets) {
            $this->scenarioTargets[$scenario] = array_combine($scenarioTargets, $scenarioTargets);
        }
        foreach ($this->targets as $key => $target) {
            if ($target['class'] === EmailTarget::class) {
                $this->targets[$key] = array_merge($target, $this->emailTargetConfig);
            }
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function updateLogTargets(): void
    {
        if ($targets = $this->getScenarioTargets()) {
            Yii::debug('Set log targets: ' . implode(',', array_keys($targets)), __METHOD__);
            $remainTargets = [];
            foreach ($this->remainTargets as $remainTargetName) {
                $remainTargets[$remainTargetName] = Yii::$app->getLog()->targets[$remainTargetName] ?? null;
            }
            Yii::$app->getLog()->targets = array_merge($targets, array_filter($remainTargets));
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
        $scenarioTargets = array_filter(array_merge(
            $this->defaultScenarioTargets['default'] ?? [],
            $this->defaultScenarioTargets[$scenario] ?? [],
            $this->scenarioTargets['default'] ?? [],
            $this->scenarioTargets[$scenario] ?? [],
        ));
        if (empty($scenarioTargets)) {
            Yii::debug("Empty defaultScenarioTargets of scenario {$scenario}.");
            return null;
        }
        foreach ($scenarioTargets as $key => $name) {
            $target = $this->targets[$name] ?? $this->defaultTargets[$name] ?? null;
            if (empty($target)) {
                throw new InvalidConfigException("Null target {$name}");
            }

            if (!($target instanceof Target)) {
                $target = Yii::createObject($target);
            }
            $scenarioTargets[$name] = $target;
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
