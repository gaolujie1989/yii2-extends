<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\generators;

/**
 * Interface DocumentGeneratorInterface
 * @package lujie\template\document\generators
 */
interface DocumentGeneratorInterface
{
    /**
     * @param string $html file content
     * @return string filePath
     * @inheritdoc
     */
    public function generate(string $html): string;
}
