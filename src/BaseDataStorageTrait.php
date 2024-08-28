<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

/**
 * Interface DataStorageInterface
 * @package lujie\data\storage
 */
trait BaseDataStorageTrait
{
    /**
     * @param array $values
     * @return mixed
     * @inheritdoc
     */
    public function multiSet(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param array $keys
     * @return mixed
     * @inheritdoc
     */
    public function multiRemove(array $keys)
    {
        foreach ($keys as $key) {
            $this->remove($key);
        }
    }
}
