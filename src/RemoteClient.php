<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;


use yii\base\BaseObject;
use yii\di\Instance;
use yii\httpclient\Client;

/**
 * Class UserRemoteClient
 * @package lujie\remote\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RemoteClient extends BaseObject
{
    /**
     * @var Client
     */
    public $client = [];

    public $baseUrl;

    public $tokenUserUrl = 'user/{token}';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->client = Instance::ensure($this->client, Client::class);
        $this->client->baseUrl = $this->baseUrl;
    }

    /**
     * @param $token
     * @param null $type
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getUserByAccessToken($token, $type = null)
    {
        $tokenUserUrl = strtr($this->tokenUserUrl, ['{token}' => $token, '{type}' => $type]);
        $response = $this->client->get($tokenUserUrl)->send();
        return $response->getData();
    }
}
