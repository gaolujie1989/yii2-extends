<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\dataLoaders;


use yii\base\BaseObject;

/**
 * Class ArrayLoader
 * @package lujie\configuration\dataLoaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayDataLoader extends BaseObject implements DataLoaderInterface
{
    public $data = [];

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function loadByKey($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function loadAll()
    {
        return $this->data;
    }
}
