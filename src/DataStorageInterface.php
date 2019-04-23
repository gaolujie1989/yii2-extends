<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


interface DataStorageInterface
{
    /**
     * @param $data
     * @return mixed
     * @inheritdoc
     */
    public function save($data);
}
