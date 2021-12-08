<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\controllers\rest;

use lujie\common\account\models\Account;
use lujie\common\oauth\OAuthAccountCallback;
use lujie\extend\rest\ActiveController;
use lujie\user\OAuthLoginCallback;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;

/**
 * Class AccountController
 * @package lujie\common\account\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountController extends ActiveController
{
    public $modelClass = Account::class;

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
     * @param ClientInterface $client
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        $OAuthLoginCallback = new OAuthAccountCallback();
        $OAuthLoginCallback->onAuthSuccess($client);
    }
}
