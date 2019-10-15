<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

/**
 * Class PhpArrayFileParser
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
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
