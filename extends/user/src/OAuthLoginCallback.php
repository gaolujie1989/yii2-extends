<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user;

use lujie\common\oauth\helpers\AuthTokenHelper;
use lujie\common\oauth\models\AuthToken;
use lujie\user\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\BaseObject;
use yii\base\UserException;
use yii\db\BaseActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class AuthSuccessCallback
 * @package lujie\common\auth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OAuthLoginCallback extends BaseObject
{
    /**
     * @var string
     */
    public $signUpUrl = '';

    /**
     * @var bool
     */
    public $autoSignUp = true;

    /**
     * @var callable
     */
    public $signUpCallback;

    /**
     * @param ClientInterface $client
     * @throws UserException
     * @inheritdoc
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        $authService = $client->getId();
        $userAttributes = $client->getUserAttributes();
        $authUserId = $userAttributes['id'] ?? 0;
        $authUsername = $userAttributes['username'] ?? '';

        $authToken = AuthTokenHelper::getAuthToken($client);

        $user = Yii::$app->getUser();
        if ($user->getIsGuest()) {
            if ($authToken !== null && $authToken->user_id) {
                $this->loginByAuth($authToken);
                return;
            }
            if ($authToken === null) {
                $authToken = new AuthToken();
                $authToken->auth_service = $authService;
                $authToken->auth_user_id = $authUserId;
                $authToken->auth_username = $authUsername;
            }
            if ($this->autoSignUp && $identity = $this->signUpByAuth($client)) {
                $authToken->user_id = $identity->getId();
                $authToken->save(false);
                $user->login($identity, Yii::$app->params['user.rememberMeDuration']);
                return;
            }
            Yii::$app->getResponse()->redirect($this->signUpUrl);
        } else {
            if ($authToken !== null) {
                $message = Yii::t('app',
                    'Unable to link {client} account. There is another user using it.',
                    ['client' => $client->getTitle()]
                );
                throw new UserException($message);
            }
            $authToken = new AuthToken();
            $authToken->auth_service = $authService;
            $authToken->auth_user_id = $authUserId;
            $authToken->auth_username = $authUsername;
            $authToken->user_id = $user->getId();
            $authToken->save(false);
        }
    }


    /**
     * @param AuthToken $authToken
     * @inheritdoc
     */
    public function loginByAuth(AuthToken $authToken): void
    {
        $user = Yii::$app->getUser();
        /** @var BaseActiveRecord $identityClass */
        $identityClass = $user->identityClass;
        /** @var IdentityInterface $identity */
        $identity = $identityClass::findOne($authToken->user_id);
        if ($identity !== null) {
            $user->login($identity, Yii::$app->params['user.rememberMeDuration'] ?? 0);
        }
    }

    /**
     * @param ClientInterface $client
     * @return IdentityInterface
     * @throws UserException
     * @inheritdoc
     */
    public function signUpByAuth(ClientInterface $client): IdentityInterface
    {
        $userAttributes = $client->getUserAttributes();
        $user = Yii::$app->getUser();
        /** @var IdentityInterface|User $identityClass */
        $identityClass = $user->identityClass;
        if ($this->signUpCallback) {
            return call_user_func($this->signUpCallback, $userAttributes);
        }

        $checkAttributes = ['username', 'email', 'phone'];
        foreach ($checkAttributes as $attribute) {
            if (isset($userAttributes[$attribute])) {
                $query = $identityClass::find()->{$attribute}($userAttributes[$attribute]);
                if ($query->exists()) {
                    $message = Yii::t('lujie/user',
                        "User with the same {attribute} as in {client} account already exists but isn't linked to it. Login first to link it.",
                        [
                            'client' => $client->getTitle(),
                            'attribute' => $attribute,
                        ]
                    );
                    throw new UserException($message);
                }
            }
        }
        /** @var BaseActiveRecord|IdentityInterface $identity */
        $identity = new $identityClass;
        $identity->setAttributes($userAttributes);
        $identity->save(false);
        return $identity;
    }
}