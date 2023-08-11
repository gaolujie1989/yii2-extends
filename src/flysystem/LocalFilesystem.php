<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\flysystem;

use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Yii;

/**
 * Class LocalFilesystem
 * @package lujie\extend\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LocalFilesystem extends Filesystem
{
    /**
     * @var string
     */
    public $path = '@statics';

    /**
     * @var string
     */
    public $cdn = '';

    /**
     * @return FilesystemAdapter
     * @inheritdoc
     */
    protected function prepareAdapter(): FilesystemAdapter
    {
        return new LocalFilesystemAdapter(Yii::getAlias($this->path));
    }

    /**
     * @param string $path
     * @param array $config
     * @return string
     * @inheritdoc
     */
    public function publicUrl(string $path, array $config = []): string
    {
        return rtrim($this->cdn, '/') . '/' . ltrim($path, '/');
    }
}
