<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\otto;

use lujie\common\account\models\Account;
use yii\base\BaseObject;

/**
 * Class BaseOttoRestClientFactory
 * @package lujie\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseOttoRestClientFactory extends BaseObject
{
    /**
     * @var array
     */
    private $_clients = [];

    public $requestOptions = [];

    /**
     * @return array
     */
    protected function getConfig(): array
    {
        return [
            'requestOptions' => $this->requestOptions,
        ];
    }

    /**
     * @param string $clientClass
     * @param Account $account
     * @return BaseOttoRestClient
     * @inheritdoc
     */
    protected function createClient(string $clientClass, Account $account): BaseOttoRestClient
    {
        $accountId = $account->account_id;
        $key = $clientClass . '-' . $account::class . '-' . $accountId;
        if (empty($this->_clients[$key])) {
            $additional = $account->additional ?: [];
            /** @var BaseOttoRestClient $client */
            $client = new $clientClass(array_merge([
                'clientId' => $additional['clientId'] ?? $account->username,
                'clientSecret' => $additional['clientSecret'] ?? $account->password,
            ], $this->getConfig()));
            $client->setId($client->getName() . '-' . $accountId);
            $this->_clients[$key] = $client;
        }
        return $this->_clients[$key];
    }
}
