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
use yii\authclient\OAuthToken;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\UserException;
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
     * @var array
     */
    public $refreshTokenExpiresIns = [];

    /**
     * @var int
     */
    public $defaultRefreshTokenExpiresIn = 86400 * 365;

    /**
     * @param ClientInterface $client
     * @return Response|null
     * @throws InvalidConfigException
     * @throws UserException
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
            throw new UserException($message);
        }

        $account = $this->getAuthingAccount($authService);
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
        $authToken->auth_user_id = $authUserId ?: $userId;
        $authToken->auth_username = $authUsername ?: $account->username;
        if ($client instanceof BaseOAuth) {
            $accessToken = $client->getAccessToken();
            $authToken->access_token = $accessToken->getToken();
            $authToken->refresh_token = $accessToken->getTokenSecret() ?: '';
            $authToken->expires_at = $accessToken->getExpireDuration() + $accessToken->createTimestamp;
            $refreshTokenExpiresIn = AuthTokenHelper::getRefreshTokenExpireDuration($accessToken)
                ?: ($this->refreshTokenExpiresIns[$authService] ?? $this->defaultRefreshTokenExpiresIn);
            $authToken->refresh_token_expires_at = $refreshTokenExpiresIn +  + $accessToken->createTimestamp;
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
    public function getAuthingAccount(string $authService): ?Account
    {
        $accountId = $this->getCacheValue($this->getAuthingAccountCacheKey($authService));
        $this->setAuthingAccount(null, $authService);
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
    public function setAuthingAccount(?Account $account, string $authService): void
    {
        if ($account === null) {
            $this->deleteCacheValue($this->getAuthingAccountCacheKey($authService));
        } else {
            $this->setCacheValue($this->getAuthingAccountCacheKey($authService), $account->id);
        }
    }

    /**
     * @param string $authService
     * @return string
     * @inheritdoc
     */
    protected function getAuthingAccountCacheKey(string $authService): string
    {
        return implode('_', [
            $this->authingAccountCacheKey,
            $authService,
            Yii::$app->getUser()->getId() ?: 0,
        ]);
    }


}