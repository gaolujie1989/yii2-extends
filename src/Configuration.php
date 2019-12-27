<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\loader\TypedFileDataLoader;
use lujie\extend\caching\CachingTrait;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\i18n\PhpMessageSource;

/**
 * Class Configuration
 * @package lujie\configuration
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Configuration extends Component implements BootstrapInterface
{
    use CachingTrait;

    public const CONFIG_TYPE_CLASS = 'classes';
    public const CONFIG_TYPE_COMPONENT = 'components';
    public const CONFIG_TYPE_BOOTSTRAP = 'bootstraps';
    public const CONFIG_TYPE_EVENT = 'events';
    public const CONFIG_TYPE_MAIN = 'main';

    /**
     * @var DataLoaderInterface
     */
    public $configLoader = [
        'class' => TypedFileDataLoader::class,
        'filePools' => ['@common/modules'],
        'typedFilePathTemplate' => '{filePool}/*/config/{type}.php',
    ];

    /**
     * @var array
     */
    public $sortConfig = ['permissions' => [['groups', 'permissions']]];

    /**
     * @var string[]
     */
    public $configScopes = ['console', 'backend', 'frontend'];

    /**
     * @var string
     */
    public $currentScope;

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'config:';

    /**
     * @var array
     */
    public $cacheTags = ['config'];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->configLoader = Instance::ensure($this->configLoader, DataLoaderInterface::class);
        $this->currentScope = $this->currentScope ?: (Yii::$app->params['scope'] ?? Yii::$app->id);
        $this->cacheKeyPrefix = ($this->cacheKeyPrefix ?: 'config:') . $this->currentScope . ':';
    }

    /**
     * @param \yii\base\Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $this->loadClassConfig();
        $this->loadComponentConfig($app);
        $this->loadMainConfig($app);
        $this->loadConfig($app);
        $this->registerEvents();
        $this->runBootstraps($app);
    }


    #region load and apply config

    /**
     * @inheritdoc
     */
    public function loadClassConfig(): void
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_CLASS)) {
            Yii::$container->setDefinitions($config);
        }
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadComponentConfig(Application $app): void
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_COMPONENT)) {
            $app->setComponents(ArrayHelper::merge(
                $this->generateDefaultI18NConfig($app),
                $config,
                $app->getComponents()
            ));
        }
    }

    /**
     * @param Application $app
     * @return array
     * @inheritdoc
     */
    protected function generateDefaultI18NConfig(Application $app): array
    {
        $translationsConfig = [];
        $modules = $app->getModules();
        foreach ($modules as $name => $module) {
            if (is_object($module)) {
                $moduleClass = get_class($module);
            } else if (is_array($module)) {
                $moduleClass = $module['class'];
            } else {
                $moduleClass = $module;
            }
            $moduleNamespace = substr($moduleClass, 0, strrpos($moduleClass, '\\'));
            $moduleNamespace = trim($moduleNamespace, '\\');
            $modulePath = strtr($moduleNamespace, ['\\' => '/']);
            $translationsConfig[$modulePath] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => "@{$modulePath}/messages",
            ];
        }
        return [
            'i18n' => [
                'translations' => $translationsConfig,
            ]
        ];
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadMainConfig(Application $app): void
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_MAIN)) {
            foreach ($config as $key => $value) {
                $app->$key = $value;
            }
        }
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadConfig(Application $app): void
    {
        if ($config = $this->getAllConfig()) {
            foreach ($config as $key => $value) {
                if (isset($app->params[$key])) {
                    $app->params[$key] = ArrayHelper::merge($value, $app->params[$key]);
                } else {
                    $app->params[$key] = $value;
                }
            }
        }
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function registerEvents(): void
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_EVENT)) {
            foreach ($config as $event) {
                Event::on($event['class'], $event['name'], $event['handler'], $event['data'] ?? null, $event['append'] ?? true);
            }
        }
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function runBootstraps(Application $app): void
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_BOOTSTRAP)) {
            foreach ($config as $mixed) {
                $component = null;
                if ($mixed instanceof \Closure
                    || (is_string($mixed) && function_exists($mixed))
                    || (is_array($mixed) && is_callable($mixed))) {
                    Yii::debug('Bootstrap with Closure', __METHOD__);
                    if (!$component = $mixed($this)) {
                        continue;
                    }
                } elseif (is_string($mixed)) {
                    if ($app->has($mixed)) {
                        $component = $app->get($mixed);
                    } elseif ($app->hasModule($mixed)) {
                        $component = $app->getModule($mixed);
                    } elseif (function_exists($mixed)) {
                        if (!$component = $mixed($this)) {
                            continue;
                        }
                    } elseif (strpos($mixed, '\\') === false) {
                        throw new InvalidConfigException("Unknown bootstrapping component ID: $mixed");
                    }
                }

                if (!isset($component)) {
                    $component = Yii::createObject($mixed);
                }

                if ($component instanceof BootstrapInterface) {
                    Yii::debug('Bootstrap with ' . get_class($component) . '::bootstrap()', __METHOD__);
                    $component->bootstrap($app);
                } else {
                    Yii::debug('Bootstrap with ' . get_class($component), __METHOD__);
                }
            }
        }
    }

    #endregion

    #region load and filter and sort config

    /**
     * @param string $configType
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getConfig(string $configType) : array
    {
        $key = $configType;
        return $this->getOrSet($key, function() use ($configType) {
            $config = $this->configLoader->get($configType) ?: [];
            $config = $this->filterConfig($configType, $config);
            return $this->sortConfig($configType, $config);
        });
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getAllConfig() : array
    {
        $key = 'all';
        return $this->getOrSet($key, function() {
            $all = $this->configLoader->all();
            foreach ($all as $configType => $config) {
                $config = $this->filterConfig($configType, $config);
                $all[$configType] = $this->sortConfig($configType, $config);
            }
            return $all;
        });
    }

    /**
     * @param string $key
     * @param array $config
     * @return array
     * @inheritdoc
     */
    protected function filterConfig(string $key, array $config): array
    {
        $scope = $this->currentScope;
        if (isset($config[$scope])) {
            $config = ArrayHelper::merge($config, $config[$scope]);
        }
        foreach ($this->configScopes as $scope) {
            unset($config[$scope]);
        }
        if (isset(Yii::$app->params[$key])) {
            $config = ArrayHelper::merge($config, Yii::$app->params[$key]);
        }
        return $config;
    }

    /**
     * @param $config
     * @return mixed
     * @inheritdoc
     */
    protected function sortConfig(string $key, array $config): array
    {
        if (empty($this->sortConfig[$key])) {
            return $config;
        }
        return static::sortByKey($config, $this->sortConfig[$key]);
    }

    /**
     * @param array $array
     * @param array $childKeys
     * @param string $sortKey
     * @return array
     * @inheritdoc
     */
    public static function sortByKey(array $array, $childKeys = ['items'], $sortKey = 'sort'): array
    {
        uasort($array, static function ($a, $b) use ($sortKey) {
            if (empty($a[$sortKey]) || empty($b[$sortKey]) || $a[$sortKey] === $b[$sortKey]) {
                return 0;
            }
            return ($a[$sortKey] < $b[$sortKey]) ? -1 : 1;
        });

        if ($childKeys) {
            if (is_array($childKeys)) {
                $childKey = array_shift($childKeys);
            } else {
                $childKey = $childKeys;
            }

            foreach ($array as $key => $childArray) {
                if (isset($childArray[$childKey])) {
                    $array[$key][$childKey] = static::sortByKey($array[$key][$childKey], $childKeys, $sortKey);
                }
            }
        }

        return $array;
    }

    #endregion
}
