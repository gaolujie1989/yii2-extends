<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\DataLoaderInterface;

/**
 * Interface DataStorageInterface
 * @package lujie\data\storage
 */
interface DataStorageInterface extends DataLoaderInterface
{
    /**
     * @param $data
     * @return mixed
     * @inheritdoc
     */
    public function set($key, $value);

    /**
     * @param $key
     * @return mixed
     * @inheritdoc
     */
    public function delete($key);
}
