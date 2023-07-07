<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\flysystem;

use DateTimeInterface;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Overtrue\Flysystem\Cos\CosAdapter;
use Yii;

/**
 * Class LocalFilesystem
 * @package lujie\extend\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QCosFilesystem extends Filesystem
{
    public $appId = 10020201024;
    public $secretId = 'AKIDsiQzQla780mQxLLU2GJCxxxxxxxxxxx';
    public $secretKey = 'b0GMH2c2NXWKxPhy77xhHgwxxxxxxxxxxx';

    public $region = 'ap-guangzhou';
    public $bucket = 'example';

    public $signedUrl = true;
    public $useHttps = true;

    public $domain;
    public $cdn;

    /**
     * @return FilesystemAdapter
     * @inheritdoc
     */
    protected function prepareAdapter(): FilesystemAdapter
    {
        $config = [
            // 必填，app_id、secret_id、secret_key
            // 可在个人秘钥管理页查看：https://console.cloud.tencent.com/capi
            'app_id' => $this->appId,
            'secret_id' => $this->secretId,
            'secret_key' => $this->secretKey,

            'region' => $this->region,
            'bucket' => $this->bucket,

            // 可选，如果 bucket 为私有访问请打开此项
            'signed_url' => $this->signedUrl,

            // 可选，是否使用 https，默认 false
            'use_https' => $this->useHttps,

            // 可选，自定义域名
            'domain' => $this->domain,

            // 可选，使用 CDN 域名时指定生成的 URL host
            'cdn' => $this->cdn,
        ];
        return new CosAdapter(array_filter($config));
    }

    /**
     * @param string $path
     * @param array $config
     * @return string
     * @throws \Overtrue\CosClient\Exceptions\InvalidConfigException
     * @inheritdoc
     */
    public function publicUrl(string $path, array $config = []): string
    {
        /** @var CosAdapter $adapter */
        $adapter = $this->getFilesystemAdapter();
        return $adapter->getUrl($path);
    }

    /**
     * @param string $path
     * @param DateTimeInterface $expiresAt
     * @param array $config
     * @return string
     * @inheritdoc
     */
    public function temporaryUrl(string $path, DateTimeInterface $expiresAt, array $config = []): string
    {
        /** @var CosAdapter $adapter */
        $adapter = $this->getFilesystemAdapter();
        return $adapter->getTemporaryUrl($path, $expiresAt);
    }
}
