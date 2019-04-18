<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


interface FileParserInterface
{
    public function parseFile(string $file) : array;
}
