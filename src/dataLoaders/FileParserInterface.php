<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\dataLoaders;


interface FileParserInterface
{
    public function parseFile(string $file) : array;
}
