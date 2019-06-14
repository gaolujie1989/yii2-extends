<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\rbac\Item;

/**
 * Class RoleForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RoleForm extends BaseItemForm
{
    /**
     * @return Item
     * @inheritdoc
     */
    public function createItem(): Item
    {
        $this->authManager->createRole($this->name);
    }
}
