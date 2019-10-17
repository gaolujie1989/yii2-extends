<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;


use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class UserRemoteClient
 * @package lujie\remote\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RemoteUserClient extends BaseObject
{
    /**
     * @var Client
     */
    public $client = Client::class;

    public $baseUrl;

    public $remoteUserUrl = 'user/info?access-token={token}';

    public $tokenHeader;

    public $dateKey = 'data';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, Client::class);
        $this->client->baseUrl = $this->baseUrl;
    }

    /**
     * @param $token
     * @param null $type
     * @return array|null
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getUserByAccessToken(string $token, string $type = null): ?array
    {
        $remoteUserUrl = strtr($this->remoteUserUrl, ['{token}' => $token, '{type}' => $type]);
        $request = $this->client->get($remoteUserUrl);
        if ($this->tokenHeader) {
            $request->addHeaders([$this->tokenHeader => $token]);
        }
        $response = $request->send();
        if ($response->getIsOk()) {
            $data = $response->getData();
            return $this->dateKey ? ArrayHelper::getValue($data, $this->dateKey) : $data;
        }
        return null;
    }
}
