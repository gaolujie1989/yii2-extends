<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file;

interface FileReaderInterface
{
    public function read(string $file) : array;
}
