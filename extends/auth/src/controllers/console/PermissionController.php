<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\auth\controllers\console;

use lujie\auth\helpers\AuthHelper;
use Yii;

/**
 * Class PermissionController
 * @package lujie\auth\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionController extends BaseAuthController
{
    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function actionSync(): void
    {
        AuthHelper::syncPermissions(Yii::$app->params['permissions'] ?? [], $this->authManager);
    }
}
