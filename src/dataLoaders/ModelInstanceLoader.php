<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\dataLoaders;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class ModelInstanceLoader
 * @package lujie\configuration\dataLoaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelInstanceLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var string|array
     */
    public $modelConfig;

    /**
     * @var DataLoaderInterface
     */
    public $dataLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataLoader = Instance::ensure($this->dataLoader, DataLoaderInterface::class);
        if (!is_array($this->modelConfig)) {
            $this->modelConfig = ['class' => $this->modelConfig];
        } elseif (empty($this->modelConfig['class'])) {
            throw new InvalidConfigException('ModelConfig class must be set.');
        }
    }

    /**
     * @param int|string $key
     * @return array|object|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadByKey($key)
    {
        $item = $this->dataLoader->loadByKey($key);
        return Yii::createObject(array_merge($this->modelConfig, $item));
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadAll()
    {
        $all = $this->dataLoader->loadAll();
        foreach ($all as $key => $item) {
            $all[$key] = Yii::createObject(array_merge($this->modelConfig, $item));
        }
        return $all;
    }
}
