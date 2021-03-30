<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\loaders;

use lujie\data\loader\BaseDataLoader;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class HttpDataLoader
 * @package lujie\template\document\loaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HttpDataLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var Client
     */
    public $client = [];

    /**
     * @var string
     */
    public $dataKey;

    /**
     * @param mixed $key
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function get($key)
    {
        if (!is_array($key)) {
            $url = strtr($this->url, ['{id}' => $key]);
        } else {
            $url = strtr($this->url, $key);
        }
        $this->client = Instance::ensure($this->client, Client::class);
        $response = $this->client->get($url)->send();
        $responseData = $response->getData();
        return $this->dataKey ? ArrayHelper::getValue($responseData, $this->dataKey) : $responseData;
    }
}
