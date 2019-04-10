<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\dataLoaders;


interface DataLoaderInterface
{
    /**
     * @param string|int $key
     * @return array|null
     * @inheritdoc
     */
    public function loadByKey($key);

    /**
     * @return array
     * @inheritdoc
     */
    public function loadAll();
}
