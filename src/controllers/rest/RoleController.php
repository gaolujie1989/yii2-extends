<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;


/**
 * Class RoleController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RoleController extends BaseItemController
{
    /**
     * @return array
     * @inheritdoc
     */
    public function actionIndex(): array
    {
        return $this->authManager->getRoles();
    }

    /**
     * @param string $name
     * @return array
     * @inheritdoc
     */
    public function actionViewUserIds($name): array
    {
        return $this->authManager->getUserIdsByRole($name);
    }
}
