<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\oauth;

use lujie\common\account\models\Account;
use lujie\common\oauth\models\AuthToken;
use lujie\extend\authclient\RestOAuth2;
use Yii;
use yii\authclient\OAuth2;
use yii\base\BaseObject;

/**
 * Class OAuthClientFactory
 * @package lujie\common\oauth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OAuthClientFactory extends BaseObject
{
    /**
     * @var array
     */
    private $_clients = [];

    /**
     * @param string $clientClass
     * @param Account $account
     * @param array $config
     * @param string|null $authService
     * @return OAuth2|null
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function createClient(string $clientClass, Account $account, array $config, ?string $authService = null): ?OAuth2
    {
        $accountId = $account->account_id;
        $key = $clientClass . '-' . $account::class . '-' . $accountId;
        if (empty($this->_clients[$key])) {
            /** @var OAuth2 $client */
            $client = new $clientClass($config);
            $client->setId($client->getName() . '-' . $accountId);
            if ($client instanceof RestOAuth2) {
                $client->setSandbox(str_contains($account->type, 'Sandbox'));
            }
            $authService = $authService ?: $account->type;
            $authToken = AuthToken::find()->userId($accountId)->authService($authService)->one();
            if ($authToken === null) {
                Yii::error("Account {$account->name} is not authed", __METHOD__);
                return null;
            }
            if ($authToken->refresh_token_expires_at && $authToken->refresh_token_expires_at - time() < 86400 * 10) {
                $day = round(($authToken->refresh_token_expires_at - time()) / 86400);
                Yii::error("Refresh token of account {$account->name} is expires in {$day} days.", __METHOD__);
                return null;
            }
            $client->setAccessTokenIfTokenIsValid([
                'params' => $authToken->additional['token'],
                'createTimestamp' => $authToken->updated_at,
            ]);
            $this->_clients[$key] = $client;
        }
        return $this->_clients[$key];
    }
}
