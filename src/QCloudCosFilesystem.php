<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem;

use creocoder\flysystem\Filesystem;
use Freyo\Flysystem\QcloudCOSv5\Adapter;
use League\Flysystem\AdapterInterface;
use lujie\flysystem\adapters\QCloudAdapter;
use Qcloud\Cos\Client;
use Xxtime\Flysystem\Aliyun\OssAdapter;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class TenyunCosFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QCloudCosFilesystem extends Filesystem
{
    public $bucket;

    public $appId;

    public $timeout;

    public $connectTimeout;

    public $region = 'ap-guangzhou';

    public $token;

    public $accessId;

    public $accessSecret;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->bucket)) {
            throw new InvalidConfigException('The "bucket" property must be set.');
        }
        if (empty($this->appId)) {
            throw new InvalidConfigException('The "appId" property must be set.');
        }
        if (empty($this->region)) {
            throw new InvalidConfigException('The "region" property must be set.');
        }
        if (empty($this->accessId)) {
            throw new InvalidConfigException('The "accessId" property must be set.');
        }
        if (empty($this->accessSecret)) {
            throw new InvalidConfigException('The "accessSecret" property must be set.');
        }
    }

    /**
     * @return AdapterInterface|OssAdapter
     * @throws \Exception
     * @inheritdoc
     */
    protected function prepareAdapter()
    {
        $config = [
            'region' => $this->region,
            'credentials' => [
                'appId' => $this->appId,
                'secretId' => $this->accessId,
                'secretKey' => $this->accessSecret,
                'token' => $this->token,
            ],

            'bucket' => $this->bucket,
            'timeout' => $this->timeout,
            'connect_timeout' => $this->connectTimeout,
            'cdn' => '',
            'scheme' => 'https',
            'read_from_cdn' => false,
            'cdn_key' => '',
            'encrypt' => false,
        ];
        return new QCloudAdapter(new Client($config), $config);
    }
}
