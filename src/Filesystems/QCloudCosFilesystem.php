<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager\Filesystems;

use BackupManager\Filesystems\Filesystem;
use League\Flysystem\Filesystem as Flysystem;
use lujie\flysystem\adapters\QCloudAdapter;
use Qcloud\Cos\Client;

/**
 * Class AliyunOssFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QCloudCosFilesystem implements Filesystem
{
    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function handles($type): bool
    {
        return strtolower($type) === 'qcloudcos';
    }

    /**
     * $config = [
     *     'region' => '',
     *     'credentials' => [
     *         'appId' => '',
     *         'secretId' => '',
     *         'secretKey' => '',
     *         'token' => '',
     *     ],
     *
     *     'bucket' => '',
     *     'timeout' => '',
     *     'connect_timeout' => '',
     *     'cdn' => '',
     *     'scheme' => 'https',
     *     'read_from_cdn' => false,
     *     'cdn_key' => '',
     *     'encrypt' => false,
     * ];
     *
     * @param array $config
     * @return Flysystem
     * @throws \Exception
     * @inheritdoc
     */
    public function get(array $config): Flysystem
    {
        return new Flysystem(new QCloudAdapter(new Client($config), $config));
    }
}
