<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class ModelInstanceLoader
 * @package lujie\data\loader
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
    public function get($key)
    {
        $item = $this->dataLoader->get($key);
        return $item ? Yii::createObject(array_merge($this->modelConfig, $item)) : null;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function all()
    {
        $all = $this->dataLoader->all();
        foreach ($all as $key => $item) {
            $all[$key] = Yii::createObject(array_merge($this->modelConfig, $item));
        }
        return $all;
    }
}
