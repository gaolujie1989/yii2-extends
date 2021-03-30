<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem;

use creocoder\flysystem\Filesystem;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\AdapterInterface;
use yii\di\Instance;

/**
 * Class CombineFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CombinedFilesystem extends Filesystem
{
    /**
     * [ 'pathPrefix' => 'fsNameOrConfig' ]
     * @var Filesystem[]
     */
    public $filesystems = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->filesystems as $key => $filesystem) {
            $this->filesystems[$key] = Instance::ensure($filesystem, Filesystem::class);
        }
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $path = $parameters[0];
        foreach ($this->filesystems as $pathPrefix => $filesystem) {
            if (strpos($path, $pathPrefix) === 0) {
                return call_user_func_array([$filesystem, $method], $parameters);
            }
        }
        return call_user_func_array([$this->filesystem, $method], $parameters);
    }

    /**
     * @return AdapterInterface|void
     * @inheritdoc
     */
    protected function prepareAdapter()
    {
        return new NullAdapter();
    }
}
