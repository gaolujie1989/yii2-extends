<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\controllers\rest;

use lujie\user\forms\LoginForm;
use lujie\user\forms\ResetPasswordByEmailForm;
use lujie\user\forms\UpdatePasswordForm;
use lujie\user\models\User;
use Yii;
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
     * @return LoginForm
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionLogin(): LoginForm
    {
        /** @var LoginForm $loginForm */
        $loginForm = Yii::createObject($this->loginForm);
        $loginForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($loginForm->login() === false && $loginForm->hasErrors() === false) {
            throw new ServerErrorHttpException('Failed to login for unknown reason.');
        }
        return $loginForm;
    }

    /**
     * @inheritdoc
     */
    public function actionLogout(): void
    {
        Yii::$app->getUser()->logout();
        if ($loginUrl = Yii::$app->getUser()->loginUrl) {
            Yii::$app->getResponse()->redirect($loginUrl);
        }
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

    /**
     * @return UpdatePasswordForm
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionUpdatePassword(): UpdatePasswordForm
    {
        $passwordForm = new UpdatePasswordForm();
        $passwordForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($passwordForm->updatePassword() === false && $passwordForm->hasErrors() === false) {
            throw new ServerErrorHttpException('Failed to update password for unknown reason.');
        }
        return $passwordForm;
    }

    /**
     * @return ResetPasswordByEmailForm
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionResetPassword(): ResetPasswordByEmailForm
    {
        $passwordForm = new ResetPasswordByEmailForm();
        $passwordForm->setScenario(ResetPasswordByEmailForm::SCENARIO_UPDATE_PASSWORD);
        $passwordForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($passwordForm->resetPassword() === false && $passwordForm->hasErrors() === false) {
            throw new ServerErrorHttpException('Failed to reset password for unknown reason.');
        }
        return $passwordForm;
    }

    /**
     * @return ResetPasswordByEmailForm
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionSendResetPasswordVerifyCode(): ResetPasswordByEmailForm
    {
        $passwordForm = new ResetPasswordByEmailForm();
        $passwordForm->setScenario(ResetPasswordByEmailForm::SCENARIO_SENDING_CODE);
        $passwordForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($passwordForm->sendVerifyCode() === false && $passwordForm->hasErrors() === false) {
            throw new ServerErrorHttpException('Failed to send verify code for unknown reason.');
        }
        return $passwordForm;
    }
}
