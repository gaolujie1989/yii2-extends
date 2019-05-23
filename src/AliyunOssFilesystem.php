<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem;

use creocoder\flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use Xxtime\Flysystem\Aliyun\OssAdapter;
use yii\base\InvalidConfigException;

/**
 * Class AliyunOssFilesystem
 * @package lujie\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AliyunOssFilesystem extends Filesystem
{
    public $bucket;

    public $endpoint;

    public $timeout;

    public $connectTimeout;

    public $isCName;

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
//        if (empty($this->endpoint)) {
//            throw new InvalidConfigException('The "endpoint" property must be set.');
//        }
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
            'bucket' => $this->bucket,
            'endpoint' => $this->endpoint,
            'timeout' => $this->timeout,
            'connectTimeout' => $this->connectTimeout,
            'isCName' => $this->isCName,
            'token' => $this->token,
            'accessId' => $this->accessId,
            'accessSecret' => $this->accessSecret,
        ];
        return new OssAdapter($config);
    }
}
