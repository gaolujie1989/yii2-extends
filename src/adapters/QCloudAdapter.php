<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem\adapters;


use Freyo\Flysystem\QcloudCOSv5\Adapter;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use Qcloud\Cos\Exception\ServiceResponseException;

/**
 * Class QCloudAdapter
 * @package lujie\flysystem\adapters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QCloudAdapter extends Adapter
{
    /**
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|false|object
     * @inheritdoc
     */
    public function writeStream($path, $resource, Config $config)
    {
        try {
            return $this->client->upload(
                $this->getBucketWithAppId(),
                $path,
                $resource,
                $this->prepareUploadConfig($config)
            );
        } catch (ServiceResponseException $e) {
            return false;
        }
    }

    #region Just Copy

    /**
     * @param Config $config
     *
     * @return array
     */
    private function prepareUploadConfig(Config $config)
    {
        $options = [];

        if (isset($this->config['encrypt']) && $this->config['encrypt']) {
            $options['ServerSideEncryption'] = 'AES256';
        }

        if ($config->has('params')) {
            $options = array_merge($options, $config->get('params'));
        }

        if ($config->has('visibility')) {
            $options['ACL'] = $this->normalizeVisibility($config->get('visibility'));
        }

        return $options;
    }

    /**
     * @param $visibility
     *
     * @return string
     */
    private function normalizeVisibility($visibility)
    {
        switch ($visibility) {
            case AdapterInterface::VISIBILITY_PUBLIC:
                $visibility = 'public-read';
                break;
        }

        return $visibility;
    }

    #endregion
}