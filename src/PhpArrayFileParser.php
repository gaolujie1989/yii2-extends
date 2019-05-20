<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


class PhpArrayFileParser implements FileParserInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function parseFile(string $file) : array
    {
        if (file_exists($file)) {
            return require($file);
        } else {
            return [];
        }
    }
}
