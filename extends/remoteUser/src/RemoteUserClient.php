<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;

use lujie\extend\helpers\HttpClientHelper;
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

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $userUrl = 'user/info';

    /**
     * @var string
     */
    public $tokenHeader = 'X-Api-Key';

    /**
     * @var string
     */
    public $dataKey = 'data';

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
     * @param string $token
     * @param string|null $type
     * @return array|null
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getUserByAccessToken(string $token, string $type = null): ?array
    {
        $remoteUserUrl = strtr($this->userUrl, ['{token}' => $token, '{type}' => $type]);
        $request = $this->client->get($remoteUserUrl);
        if ($this->tokenHeader) {
            $request->addHeaders([$this->tokenHeader => $token]);
        }
        $data = HttpClientHelper::sendRequest($request, [], 1)->getData();
        return $this->dataKey ? ArrayHelper::getValue($data, $this->dataKey) : $data;
    }
}
