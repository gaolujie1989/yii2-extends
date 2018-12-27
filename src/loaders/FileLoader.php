<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration\loaders;


use lujie\configuration\Configuration;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class FileLoader
 * @package lujie\configuration\loaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class FileLoader extends BaseObject implements ConfigLoaderInterface
{
    /**
     * @var array
     */
    public $codePools = ['@modules', '@common/modules'];

    /**
     * @var string
     */
    public $configFolder = 'config';

    /**
     * @var string
     */
    public $fileSuffix = '.php';

    /**
     * @var array
     */
    protected $configTypeFiles = [
        Configuration::CONFIG_TYPE_CLASS => 'classes',
        Configuration::CONFIG_TYPE_COMPONENT => 'components',
        Configuration::CONFIG_TYPE_MAIN => 'main',
    ];

    /**
     * @param null $configType
     * @return array
     * @inheritdoc
     */
    public function loadConfig($configType = null) : array
    {
        $fileName = ($configType && $this->configTypeFiles[$configType]) ? $this->configTypeFiles[$configType] : '*';
        $configData = $this->getConfigData($fileName);
        return $configType ? ($configData[$configType] ?? []) : $configData;
    }

    /**
     * @param string $fileName
     * @return array
     * @inheritdoc
     */
    protected function getConfigFiles($fileName = '*') : array
    {
        $configFiles = [];
        foreach ($this->codePools as $codePool) {
            $codePool = Yii::getAlias($codePool);
            $files = glob("$codePool/*/{$this->configFolder}/{$fileName}.{$this->fileSuffix}");
            $configFiles = array_merge($configFiles, $files);
        }
        array_unique($configFiles);
        return $configFiles;
    }

    /**
     * @param string $fileName
     * @return array
     * @inheritdoc
     */
    protected function getConfigData($fileName = '*') : array
    {
        $configData = [];
        $configFiles = $this->getConfigFiles($fileName);
        foreach ($configFiles as $configFile) {
            $basename = basename($configFile, $this->fileSuffix);
            if (isset($configData[$basename])) {
                $configData[$basename] = ArrayHelper::merge($configData[$basename], $this->parseConfig($configFile));
            } else {
                $configData[$basename] = $this->parseConfig($configFile);
            }
        }
        return $configData;
    }

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    abstract protected function parseConfig(string $file) : array;
}