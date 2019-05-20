<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


interface DataLoaderInterface
{
    /**
     * @param string|int $key
     * @return mixed|null
     * @inheritdoc
     */
    public function get($key);

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): ?array;
}
