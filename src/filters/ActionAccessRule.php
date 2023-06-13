<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\filters;

use yii\base\Action;
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
 *              'class' => '\yii\auth\filters\AccessRule',
 *              'controllers' => ['user']
 *              'action' => ['login', 'reset-password', 'send-reset-password-verify-code']
 *              'roles' => ['?']
 *              'allow' => true,
 *          ],
 *          [
 *              'class' => '\yii\auth\filters\AccessRule',
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
    /**
     * @var ?callable
     */
    public $actionPermissionNameCallback = 'formatActionId';

    /**
     * @var string
     */
    public $prefix = '';

    /**
     * @var string
     */
    public $suffix = '';

    /**
     * @var array
     */
    public $replaces = ['/' => '_'];

    /**
     * @var bool
     */
    public $supportPrefix = false;

    /**
     * @var string
     */
    public $separator = '_';

    /**
     * @param Action $action
     * @param bool|\yii\web\User $user
     * @param \yii\web\Request $request
     * @return bool|null
     * @inheritdoc
     */
    public function allows($action, $user, $request): ?bool
    {
        $this->appendActionIdToPermissions($action);
        return parent::allows($action, $user, $request);
    }

    /**
     * @param Action $action
     * @inheritdoc
     */
    protected function appendActionIdToPermissions(Action $action): void
    {
        $actionId = $action->getUniqueId();
        if ($this->actionPermissionNameCallback) {
            if (method_exists($this, $this->actionPermissionNameCallback)) {
                $actionId = $this->{$this->actionPermissionNameCallback}($actionId);
            } else if (is_callable($this->actionPermissionNameCallback)) {
                $actionId = call_user_func($this->actionPermissionNameCallback, $actionId);
            }
        }
        $actionId = strtr($actionId, $this->replaces);
        $this->permissions = $this->permissions ?: [];
        $this->permissions[] = $this->prefix . $actionId . $this->suffix;
        if ($this->supportPrefix && $this->separator) {
            $actionIdParts = explode($this->separator, $actionId);
            array_shift($actionIdParts);
            while($actionIdParts) {
                $prefixActionId = implode($this->separator, $actionIdParts);
                $this->permissions[] = $this->prefix . $prefixActionId . $this->suffix;
                array_shift($actionIdParts);
            }
        }
    }

    /**
     * @param string $actionId
     * @return string
     * @inheritdoc
     */
    public function formatActionId(string $actionId): string
    {
        //replace from xxx-controller/xxx-action => xxxController/xxxAction
        return preg_replace_callback('/-([a-z0-9_])/i', static function ($matches) {
            return ucfirst($matches[1]);
        }, $actionId);
    }
}
