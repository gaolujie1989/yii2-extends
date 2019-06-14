<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\rbac\Item;

/**
 * Class PermissionForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionForm extends BaseItemForm
{
    /**
     * @return Item
     * @inheritdoc
     */
    public function createItem(): Item
    {
        $this->authManager->createPermission($this->name);
    }
}
