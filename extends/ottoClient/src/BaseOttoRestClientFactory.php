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
    private static $_clients = [];

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
        if (empty(self::$_clients[$key])) {
            /** @var BaseOttoRestClient $client */
            $client = new $clientClass(array_merge([
                'username' => $account->username,
                'password' => $account->password,
            ], $this->getConfig()));
            $client->setId($client->getName() . '-' . $accountId);
            self::$_clients[$key] = $client;
        }
        return self::$_clients[$key];
    }
}
