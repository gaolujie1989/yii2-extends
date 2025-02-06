<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file;

/**
 * Interface FileReaderInterface
 * @package lujie\extend\file
 */
interface FileReaderInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array;

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array;
}
