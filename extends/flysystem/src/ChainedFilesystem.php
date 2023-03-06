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
 * Class ChainedFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChainedFilesystem extends Filesystem
{
    /**
     * [ 'name' => 'fsNameOrConfig' ]
     * @var Filesystem[]
     */
    public $filesystems = [];

    /**
     * @var array
     */
    protected $needCheckFileExistMethods = [
        'copy', 'delete',
        'getMetadata', 'getMimetype', 'getSize', 'getTimestamp', 'getVisibility', 'setVisibility',
        'put', 'putStream',
        'read', 'readAndDelete', 'readStream',
        'rename', 'update', 'updateStream',
    ];

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
        $needCheckFileExists = in_array($method, $this->needCheckFileExistMethods, true);
        foreach ($this->filesystems as $filesystem) {
            if ($needCheckFileExists === false || $filesystem->has($parameters[0])) {
                $result = call_user_func_array([$filesystem, $method], $parameters);
                if ($result !== false) {
                    return $result;
                }
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
