<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file;

/**
 * Interface FileWriterInterface
 * @package lujie\extend\file
 */
interface FileWriterInterface
{
    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function write(string $file, array $data): void;
}
