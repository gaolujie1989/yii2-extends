<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\configuration\loaders;


interface ConfigLoaderInterface
{
    /**
     * load config data
     * @param string|null $configType
     * @return array
     * @inheritdoc
     */
    public function loadConfig($configType = null) : array;
}