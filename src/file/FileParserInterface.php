<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file;

interface FileParserInterface
{
    public function parseFile(string $file) : array;
}
