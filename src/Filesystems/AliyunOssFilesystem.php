<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager\Filesystems;

use BackupManager\Filesystems\Filesystem;
use League\Flysystem\Filesystem as Flysystem;
use Xxtime\Flysystem\Aliyun\OssAdapter;

/**
 * Class AliyunOssFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AliyunOssFilesystem implements Filesystem
{
    /**
     * @param $type
     * @return bool
     * @inheritdoc
     */
    public function handles($type): bool
    {
        return strtolower($type) === 'aliyunoss';
    }

    /**
     *  $config = [
     *      'bucket' => '',
     *      'endpoint' => '',
     *      'timeout' => '',
     *      'connectTimeout' => '',
     *      'isCName' => '',
     *      'token' => '',
     *      'accessId' => '',
     *      'accessSecret' => '',
     *  ];
     *
     * @param array $config
     * @return Flysystem
     * @throws \Exception
     * @inheritdoc
     */
    public function get(array $config): Flysystem
    {
        return new Flysystem(new OssAdapter($config));
    }
}
