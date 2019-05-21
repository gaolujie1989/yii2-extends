<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\parsers;


interface ParserInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function parse(string $file): array;
}
