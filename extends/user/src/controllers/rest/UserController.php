<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\controllers\rest;

use lujie\extend\rest\MethodAction;
use lujie\user\forms\LoginForm;
use lujie\user\forms\PasswordResetByEmailForm;
use lujie\user\forms\PasswordUpdateForm;
use lujie\user\models\User;
use lujie\user\OAuthLoginCallback;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\di\Instance;
use yii\rest\Controller;
use yii\web\IdentityInterface;
use yii\web\ServerErrorHttpException;

/**
 * Class UserController
 * @package lujie\user\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserController extends Controller
{
    public $loginForm = LoginForm::class;

    /**
     * @var array
     */
    public $authLoginCallback = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'login' => [
                'class' => MethodAction::class,
                'modelClass' => LoginForm::class,
                'method' => 'login',
            ],
            'update-password' => [
                'class' => MethodAction::class,
                'modelClass' => PasswordUpdateForm::class,
                'method' => 'update',
            ],
        ]);
    }

    /**
     * @param ClientInterface $client
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UserException
     * @inheritdoc
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        /** @var OAuthLoginCallback $OAuthLoginCallback */
        $OAuthLoginCallback = Instance::ensure($this->authLoginCallback, OAuthLoginCallback::class);
        $OAuthLoginCallback->onAuthSuccess($client);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function actionLogout(): bool
    {
        return Yii::$app->getUser()->logout();
    }

    /**
     * @return IdentityInterface|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionViewInfo(): ?IdentityInterface
    {
        return Yii::$app->getUser()->getIdentity();
    }

    /**
     * @return User
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionUpdateInfo(): User
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->getIdentity();
        $user->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($user->save() === false && $user->hasErrors() === false) {
            throw new ServerErrorHttpException('Failed to update info for unknown reason.');
        }
        return $user;
    }
}
