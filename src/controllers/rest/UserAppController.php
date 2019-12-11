<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\controllers\rest;

use lujie\user\forms\AppLoginForm;
use lujie\user\forms\LoginForm;
use Yii;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

/**
 * Class UserController
 * @package lujie\user\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserAppController extends Controller
{
    public $loginForm = AppLoginForm::class;

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
}
