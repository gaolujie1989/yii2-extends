<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\filters;

use yii\base\Action;
use yii\base\Application;
use yii\filters\AccessRule;

/**
 * Class ActionAccessRule
 *
 * use with AccessControl, set as App behavior,
 * for login allow guest user
 * for update user info allow login user
 * for other, check action access with access checker, config in user
 * [
 *  'as authAccessControl' => [
 *      'class' => '\yii\filters\AccessControl',
 *      'rules' => [
 *          [
 *              'class' => 'lujie\auth\filters\AccessRule',
 *              'controllers' => ['user']
 *              'action' => ['login', 'reset-password', 'send-reset-password-verify-code']
 *              'roles' => ['?']
 *              'allow' => true,
 *          ],
 *          [
 *              'class' => 'lujie\auth\filters\AccessRule',
 *              'controllers' => ['user']
 *              'action' => ['logout', 'update', 'update-password']
 *              'roles' => ['@']
 *              'allow' => true,
 *          ],
 *          [
 *              'class' => 'lujie\auth\filters\ActionAccessRule',
 *              'allow' => true,
 *          ],
 *      ],
 *  ]
 * ]
 *
 * @package lujie\auth\filters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActionAccessRule extends AccessRule
{
    public $actionPermissionNameCallback;

    /**
     * @param Action $action
     * @param false|\yii\web\User $user
     * @param \yii\web\Request $request
     * @return bool|null
     * @inheritdoc
     */
    public function allows($action, $user, $request): array
    {
        $actionId = $action->getUniqueId();
        if ($this->actionPermissionNameCallback && is_callable($this->actionPermissionNameCallback)) {
            $actionId = call_user_func($this->actionPermissionNameCallback, $actionId);
        }
        $this->permissions = $this->permissions ?: [];
        $this->permissions[] = $actionId;
        return parent::allows($action, $user, $request);
    }
}
