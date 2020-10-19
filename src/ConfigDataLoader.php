<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration;


use lujie\data\loader\BaseDataLoader;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ConfigLoader
 * @package lujie\configuration
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConfigDataLoader extends BaseDataLoader
{
    /**
     * @var Configuration
     */
    public $configuration = 'configuration';

    /**
     * @var string
     */
    public $configType;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->configuration = Instance::ensure($this->configuration, Configuration::class);
    }

    /**
     * @param int|mixed|string $key
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        $config = $this->configuration->getConfig($this->configType);
        return ArrayHelper::getValue($config, $key);
    }

    /**
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function all(): ?array
    {
        return $this->configuration->getConfig($this->configType);
    }
}