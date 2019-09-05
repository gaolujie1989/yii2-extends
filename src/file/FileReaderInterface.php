<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file;

interface FileReaderInterface
{
    public function parseFile(string $file) : array;
}
