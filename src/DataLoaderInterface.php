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
     * @param array $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet(array $keys): array;

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): ?array;

    /**
     * @return \Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): \Iterator;
}
