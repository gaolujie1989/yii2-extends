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
     * @param string|int $key
     * @param mixed $value
     * @return mixed
     * @inheritdoc
     */
    public function set($key, $value);

    /**
     * @param string|int $key
     * @return mixed
     * @inheritdoc
     */
    public function remove($key);
}
