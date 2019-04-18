<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


class PhpArrayFileParser
{
    /**
     * @param string $file
     * @return mixed
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
