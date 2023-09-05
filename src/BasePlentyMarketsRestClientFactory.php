<?php

namespace lujie\plentyMarkets;

use yii\base\BaseObject;

/**
* This class is autogenerated by the OpenAPI gii generator
*/
class BasePlentyMarketsRestClientFactory extends BaseObject
{
    public $apiBaseUrl;
    public $username;
    public $password;

    /**
     * @var array
     */
    private static $_clients = [];

    /**
     * @return array
     */
    protected function getConfig(): array
    {
        return [
            'apiBaseUrl' => $this->apiBaseUrl,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    /**
     * @param string $clientClass
     * @return BasePlentyMarketsRestClient
     * @inheritdoc
     */
    protected function createClient(string $clientClass): BasePlentyMarketsRestClient
    {
        $key = $clientClass;
        if (empty(self::$_clients[$key])) {
            /** @var BasePlentyMarketsRestClient $client */
            $client = new $clientClass($this->getConfig());
            self::$_clients[$key] = $client;
        }
        return self::$_clients[$key];
    }
}
