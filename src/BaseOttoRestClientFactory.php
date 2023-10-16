<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\otto;

use yii\base\BaseObject;

/**
 * Class BaseOttoRestClientFactory
 * @package lujie\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseOttoRestClientFactory extends BaseObject
{
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
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    /**
     * @param string $clientClass
     * @return BaseOttoRestClient
     * @inheritdoc
     */
    protected function createClient(string $clientClass): BaseOttoRestClient
    {
        $key = $clientClass;
        if (empty(self::$_clients[$key])) {
            /** @var BaseOttoRestClient $client */
            $client = new $clientClass($this->getConfig());
            self::$_clients[$key] = $client;
        }
        return self::$_clients[$key];
    }
}
