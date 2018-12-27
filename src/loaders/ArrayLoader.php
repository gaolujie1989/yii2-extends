<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration\loaders;


/**
 * Class ArrayLoader
 * @package lujie\configuration\loaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayLoader extends FileLoader
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    protected function parseConfig(string $file): array
    {
        return require($file);
    }
}