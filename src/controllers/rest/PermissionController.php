<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

/**
 * Class PermissionController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionController extends BaseItemController
{
    /**
     * @return array
     * @inheritdoc
     */
    public function actionIndex(): array
    {
        return $this->authManager->getPermissions();
    }
}
