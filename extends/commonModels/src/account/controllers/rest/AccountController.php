<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\controllers\rest;

use lujie\common\account\models\Account;
use lujie\common\oauth\OAuthAccountCallback;
use lujie\extend\rest\ActiveController;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\di\Instance;

/**
 * Class AccountController
 * @package lujie\common\account\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountController extends ActiveController
{
    public $modelClass = Account::class;

    /**
     * @var array
     */
    public $authAccountCallback = [];

    /**
     * @var string
     */
    public $accountIdGetParamName = 'id';

    public $clientIdGetParamName = 'authclient';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authAccountCallback['accountClass'] = $this->modelClass;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ]
        ]);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if ($action->id === 'auth') {
            $this->setAuthingAccountBeforeAuth();
        }
        return parent::beforeAction($action);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    protected function setAuthingAccountBeforeAuth(): void
    {
        /** @var OAuthAccountCallback $OAuthLoginCallback */
        $OAuthLoginCallback = Instance::ensure($this->authAccountCallback, OAuthAccountCallback::class);
        $accountId = Yii::$app->getRequest()->getQueryParam($this->accountIdGetParamName);
        $authService = Yii::$app->getRequest()->getQueryParam($this->clientIdGetParamName);
        if ($accountId) {
            /** @var Account $account */
            $account = $this->findModel($accountId);
            $OAuthLoginCallback->setAuthingAccount($account, $authService);
        }
    }

    /**
     * @param ClientInterface $client
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UserException
     * @inheritdoc
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        /** @var OAuthAccountCallback $OAuthLoginCallback */
        $OAuthLoginCallback = Instance::ensure($this->authAccountCallback, OAuthAccountCallback::class);
        $OAuthLoginCallback->onAuthSuccess($client);
    }
}
