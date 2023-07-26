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

/**
 * Class OAuthClientFactory
 * @package lujie\common\oauth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OAuthClientFactory
{
    /**
     * @var array
     */
    private static $_clients = [];

    /**
     * @param string $clientClass
     * @param Account $account
     * @param array $config
     * @return OAuth2|null
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public static function createClient(string $clientClass, Account $account, array $config): ?OAuth2
    {
        $accountId = $account->account_id;
        $key = $clientClass . '-' . $account::class . '-' . $accountId;
        if (empty(self::$_clients[$key])) {
            /** @var OAuth2 $client */
            $client = new $clientClass($config);
            $client->setId($client->getName() . '-' . $accountId);
            if ($client instanceof RestOAuth2) {
                $client->setSandbox(str_contains($account->type, 'Sandbox'));
            }
            $authToken = AuthToken::find()->userId($accountId)->one();
            if ($authToken === null) {
                Yii::error("Account {$account->name} is not authed", __METHOD__);
                return null;
            }
            if ($authToken->refresh_token_expires_at && $authToken->refresh_token_expires_at < time()) {
                Yii::error("Refresh token of account {$account->name} is expired", __METHOD__);
                return null;
            }
            $client->setAccessTokenIfTokenIsValid([
                'params' => $authToken->additional['token'],
                'createTimestamp' => $authToken->updated_at,
            ]);
            self::$_clients[$key] = $client;
        }
        return self::$_clients[$key];
    }
}
