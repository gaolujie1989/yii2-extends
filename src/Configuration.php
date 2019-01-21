<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration;


use lujie\configuration\loaders\ArrayLoader;
use lujie\configuration\loaders\ConfigLoaderInterface;
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
    const CONFIG_TYPE_CLASS = 'classes';
    const CONFIG_TYPE_COMPONENT = 'components';
    const CONFIG_TYPE_MAIN = 'main';
    const CONFIG_TYPE_EVENT = 'events';

    /**
     * @var ConfigLoaderInterface
     */
    public $configLoader = [
        'class' => ArrayLoader::class,
    ];

    /**
     * @var array
     */
    public $sortConfig = ['menus' => [], 'permissions' => [['groups', 'permissions']]];

    /**
     * @var Cache
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cacheTag = 'configuration';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->configLoader = Instance::ensure($this->configLoader);
        if ($this->cache) {
            $this->cache = Instance::ensure($this->cache);
        }
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
        if ($config = $this->getConfig()) {
            foreach ($config as $key => $value) {
                if (isset($app->params[$key])) {
                    $app->params[$key] = ArrayHelper::merge($app->params[$key], $value);
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
     * @param null $configType
     * @return array
     * @inheritdoc
     */
    protected function getConfig($configType = null): array
    {
        $callable = function () use ($configType) {
            $config = $this->sortConfig($this->filterConfig($this->configLoader->loadConfig($configType)));
            return $configType ? $config[$configType] : $config;
        };
        if ($this->cache) {
            $cacheKey =  __METHOD__ . ($configType ?: 'ALL');
            $dependency = new TagDependency(['tags' => $this->cacheTag]);
            return $this->cache->getOrSet($cacheKey, $callable, 0, $dependency);
        } else {
            return call_user_func($callable);
        }
    }

    /**
     * @param array $config
     * @return array
     * @inheritdoc
     */
    protected function filterConfig(array $config): array
    {
        $scope = Yii::$app->params['scope'] ?? Yii::$app->id;
        foreach ($config as $key => $value) {
            //overwrite scope config to global config
            if (isset($value[$scope])) {
                $value = ArrayHelper::merge($value, $value[$scope]);
            }
            //filter, remove frontend/backend config
            $value = array_filter($value, function ($k) {
                return substr($k, -3) !== 'end';
            }, ARRAY_FILTER_USE_KEY);
            //overwrite by yii params config
            if (isset(Yii::$app->params[$key])) {
                $value = ArrayHelper::merge($value, Yii::$app->params[$key]);
            }
            $config[$key] = $value;
        }
        return $config;
    }

    /**
     * @param $config
     * @return mixed
     * @inheritdoc
     */
    protected function sortConfig(array $config): array
    {
        //sort config
        foreach ($this->sortConfig as $key => $sortKeys) {
            if (isset($config[$key])) {
                array_unshift($sortKeys, $config[$key]);
                $config[$key] = call_user_func_array([$this, 'sortByKey'], $sortKeys);
            }
        }
        return $config;
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
