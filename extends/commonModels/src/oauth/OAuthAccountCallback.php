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
     * @var bool
     */
    public $returnResponse = true;

    /**
     * @var bool
     */
    public $allowCreateAccount = true;

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
            if (!$this->allowCreateAccount) {
                throw new UserException('Unable to link account. Please create first.');
            }
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
            $authToken->refresh_token_expires_at = AuthTokenHelper::getRefreshTokenExpireDuration($accessToken) + $accessToken->createTimestamp;
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
        $accountId = $this->getCacheValue($authService);
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
            $this->deleteCacheValue($authService);
        } else {
            $this->setCacheValue($authService, $account->id);
        }
    }
}
