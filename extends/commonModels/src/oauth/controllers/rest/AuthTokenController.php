<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\oauth\controllers\rest;

use lujie\common\account\models\Account;
use lujie\common\oauth\models\AuthToken;
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
class AuthTokenController extends ActiveController
{
    public $modelClass = AuthToken::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return array_intersect_key($actions, array_flip(['index']));
    }
}
