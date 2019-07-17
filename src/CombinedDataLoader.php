<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;

/**
 * Class CombinedDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CombinedDataLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var DataLoaderInterface[]
     */
    public $dataLoaders;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->dataLoaders as $key => $dataLoader) {
            $this->dataLoaders[$key] = Instance::ensure($dataLoader, DataLoaderInterface::class);
        }
    }

    /**
     * @param int|string $key
     * @return mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        foreach ($this->dataLoaders as $dataLoader) {
            $value = $dataLoader->get($key);
            if ($value !== null) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('The method `all` not support for CombinedDataLoader');
    }
}
