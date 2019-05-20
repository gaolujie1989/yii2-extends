<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ObjectDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ObjectedDataLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $dataLoader;

    /**
     * @var array
     */
    public $objectConfig = [];

    /**
     * @var string
     */
    public $objectClass;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataLoader = Instance::ensure($this->dataLoader, DataLoaderInterface::class);
        if (empty($this->objectClass)) {
            throw new InvalidConfigException('Object class must be set');
        }
    }

    /**
     * @param int|string $key
     * @return mixed|object|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        $data = $this->dataLoader->get($key);
        return $this->createObject($data);
    }

    /**
     * @return array|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function all(): ?array
    {
        $all = $this->dataLoader->all();
        foreach ($all as $key => $item) {
            $all[$key] = $this->createObject($item);
        }
        return $all;
    }

    /**
     * @param array|null $data
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    private function createObject(?array $data): ?object
    {
        if ($data === null) {
            return null;
        }
        $object = ArrayHelper::toArray($data, $this->objectConfig);
        if (empty($object['class'])) {
            $object['class'] = $this->objectClass;
        }
        return Yii::createObject($object);
    }
}
