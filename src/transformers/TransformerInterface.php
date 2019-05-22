<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

interface TransformerInterface
{
    /**
     * @param array $data
     * @return array|null
     * @inheritdoc
     */
    public function transform(array $data): array;
}
