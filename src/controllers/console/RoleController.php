<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\auth\controllers\console;

use lujie\auth\helpers\AuthHelper;
use Yii;

/**
 * Class RoleController
 * @package lujie\auth\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RoleController extends BaseAuthController
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionSync(): void
    {
        AuthHelper::syncRules(Yii::$app->params['rules'] ?? [], $this->authManager);
    }
}
