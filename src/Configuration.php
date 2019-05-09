<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration;


use lujie\configuration\loaders\ArrayLoader;
use lujie\configuration\loaders\ConfigLoaderInterface;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\loader\FileDataLoader;
use lujie\data\loader\PhpArrayFileParser;
use lujie\data\loader\TypedFileDataLoader;
use lujie\extend\caching\CachingTrait;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class Configuration
 * @package lujie\configuration
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Configuration extends Component implements BootstrapInterface
{
    use CachingTrait;

    const CONFIG_TYPE_CLASS = 'classes';
    const CONFIG_TYPE_COMPONENT = 'components';
    const CONFIG_TYPE_EVENT = 'events';
    const CONFIG_TYPE_MAIN = 'main';

    /**
     * @var DataLoaderInterface
     */
    public $configLoader = [
        'class' => TypedFileDataLoader::class,
        'filePools' => ['@common/modules'],
        'filePathTemplate' => '{filePool}/*/config/{type}.php',
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->configLoader = Instance::ensure($this->configLoader, DataLoaderInterface::class);
        $this->currentScope = Yii::$app->params['scope'] ?? Yii::$app->id;
        $this->cacheKeyPrefix = ($this->cacheKeyPrefix ?: 'config:') . $this->currentScope . ':';
        $this->initCache();
    }

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->loadClassConfig();
        $this->loadComponentConfig($app);
        $this->loadMainConfig($app);
        $this->loadConfig($app);
        $this->registerEvents();
    }


    #region load and apply config

    /**
     * @inheritdoc
     */
    public function loadClassConfig()
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_CLASS)) {
            Yii::$container->setDefinitions($config);
        }
    }

    /**
     * @param \yii\base\Application $app
     */
    public function loadComponentConfig($app)
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
     * @param \yii\base\Application $app
     * @return array
     * @inheritdoc
     */
    protected function generateDefaultI18NConfig($app)
    {
        $translationsConfig = [];
        $modules = $app->getModules(false);
        foreach ($modules as $name => $module) {
            $moduleClass = is_array($module) ? $module['class'] : $module;
            $moduleNamespace = substr($moduleClass, 0, strrpos($moduleClass, '\\'));
            $moduleNamespace = trim($moduleNamespace, '\\');
            $modulePath = strtr($moduleNamespace, ['\\' => '/']);
            $translationsConfig[$modulePath] = [
                'class' => 'yii\i18n\PhpMessageSource',
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
     * @param \yii\base\Application $app
     */
    public function loadMainConfig($app)
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_MAIN)) {
            foreach ($config as $key => $value) {
                $app->$key = $value;
            }
        }
    }

    /**
     * @param \yii\base\Application $app
     */
    public function loadConfig($app)
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
     * @inheritdoc
     */
    public function registerEvents()
    {
        if ($config = $this->getConfig(self::CONFIG_TYPE_EVENT)) {
            foreach ($config as $event) {
                $event = array_merge(['data' => null, 'append' => true], $event);
                Event::on($event['class'], $event['name'], $event['handler'], $event['data'], $event['append']);
            }
        }
    }

    #endregion

    #region load and filter and sort config

    /**
     * @param string $configType
     * @return array
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
     * @param $array
     * @param string $childKeys
     * @param string $sortKey
     * @return mixed
     */
    public static function sortByKey($array, $childKeys = 'items', $sortKey = 'sort')
    {
        uasort($array, function ($a, $b) use ($sortKey) {
            if (empty($a[$sortKey]) || empty($b[$sortKey]) || $a[$sortKey] == $b[$sortKey]) {
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
