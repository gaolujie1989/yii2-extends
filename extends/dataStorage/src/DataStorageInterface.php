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
     * @param array $values
     * @return mixed
     * @inheritdoc
     */
    public function multiSet(array $values);

    /**
     * @param string|int $key
     * @return mixed
     * @inheritdoc
     */
    public function remove($key);

    /**
     * @param array $keys
     * @return mixed
     * @inheritdoc
     */
    public function multiRemove(array $keys);
}
