<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file;

interface FileWriterInterface
{
    public function write(string $file, array $data): void;
}
