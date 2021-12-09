<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\oauth;

use lujie\common\account\models\Account;
use lujie\common\oauth\helpers\AuthTokenHelper;
use lujie\common\oauth\models\AuthToken;
use lujie\extend\caching\CachingTrait;
use Yii;
use yii\authclient\BaseOAuth;
use yii\authclient\ClientInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\Response;

/**
 * Class AccountAuthCallback
 * @package lujie\common\oauth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OAuthAccountCallback extends BaseObject
{
    use CachingTrait;

    public $cacheByUserLevel = true;

    /**
     * @var Account
     */
    public $accountClass;

    /**
     * @var array
     */
    public $accountTypes = [];

    /**
     * @var string
     */
    public $authingAccountCacheKey = 'authing_account';

    /**
     * @var bool
     */
    public $returnResponse = true;

    /**
     * @param ClientInterface $client
     * @return Response|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function onAuthSuccess(ClientInterface $client): ?Response
    {
        $authService = $client->getId();
        $userAttributes = $client->getUserAttributes();
        $authUserId = $userAttributes['id'] ?? 0;
        $authUsername = $userAttributes['username'] ?? '';

        if (AuthTokenHelper::getAuthToken($client)) {
            $message = Yii::t('lujie/common',
                'Unable to link {client} account. There is another user using it.',
                ['client' => $client->getTitle()]
            );
            throw new InvalidArgumentException($message);
        }

        $account = $this->getAuthingAccount();
        if ($account === null) {
            $account = new $this->accountClass();
            $account->type = $this->accountTypes[$authService] ?? $authService;
            $account->name = $authService . ':' . $authUsername;
            $account->username = $authUsername;
            $account->save(false);
        }

        $userId = $account->account_id;
        $authToken = AuthToken::find()->authService($authService)->userId($userId)->one();
        if ($authToken === null) {
            $authToken = new AuthToken();
            $authToken->user_id = $userId;
            $authToken->auth_service = $authService;
        }
        $authToken->auth_user_id = $authUserId;
        $authToken->auth_username = $authUsername;
        if ($client instanceof BaseOAuth) {
            $accessToken = $client->getAccessToken();
            $authToken->access_token = $accessToken->getToken();
            $authToken->refresh_token = $accessToken->getTokenSecret() ?: '';
            $authToken->expires_at = $accessToken->getExpireDuration() + $accessToken->createTimestamp;
            $authToken->additional = array_merge($authToken->additional ?: [], ['token' => $accessToken->getParams()]);
        }
        $authToken->save(false);

        if ($this->returnResponse) {
            $response = Yii::$app->getResponse();
            $response->data = ['status' => 'success'];
            return $response;
        }
        return null;
    }

    /**
     * @return Account|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getAuthingAccount(): ?Account
    {
        $accountId = $this->getCacheValue($this->authingAccountCacheKey);
        $this->setAuthingAccount(null);
        if ($accountId) {
            return $this->accountClass::findOne($accountId);
        }
        return null;
    }

    /**
     * @param Account|null $account
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function setAuthingAccount(?Account $account): void
    {
        if ($account === null) {
            $this->deleteCacheValue($this->authingAccountCacheKey);
        } else {
            $this->setCacheValue($this->authingAccountCacheKey, $account->id);
        }
    }
}