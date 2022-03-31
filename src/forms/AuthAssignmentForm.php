<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use lujie\auth\controllers\rest\PermissionController;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\BaseManager;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * Class AuthAssignmentForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthAssignmentForm extends Model
{
    /**
     * @var IdentityInterface
     */
    public $userClass;

    /**
     * @var int
     */
    public $userId;

    /**
     * 理论上不应该直接给特定用户直接指派权限，会造成后期权限管理混乱
     * @var string[]
     */
    public $roles;

    /**
     * @var Role[]
     */
    private $_roles;

    /**
     * @var BaseManager
     */
    public $authManager = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, BaseManager::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'validateUser'],
            [['roles'], 'each', 'rule' => ['string']],
            [['roles'], 'validateRoles'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateUser(): void
    {
        $identity = $this->userClass::findIdentity($this->userId);
        if ($identity === null) {
            $this->addError('userId', 'Invalid userId');
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function validateRoles(): void
    {
        $invalidRoleNames = [];
        $this->_roles = [];
        foreach ($this->roles as $roleName) {
            $role = $this->authManager->getRole($roleName);
            if ($role === null) {
                $invalidRoleNames[] = $roleName;
            } else {
                $this->_roles[$roleName] = $role;
            }
        }
        if ($invalidRoleNames) {
            $this->addError('roles', 'Invalid roles:' . implode(',', $invalidRoleNames));
        }
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function assign(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        if (!is_array($this->roles)) {
            return true;
        }
        if (empty($this->roles)) {
            $this->authManager->revokeAll($this->userId);
            return true;
        }
        $assignedRoles = $this->authManager->getRolesByUser($this->userId);
        $assignedRoles = ArrayHelper::index($assignedRoles, 'name');
        foreach ($this->_roles as $roleName => $role) {
            if (isset($assignedRoles[$roleName])) {
                unset($assignedRoles[$roleName]);
            } else {
                $this->authManager->assign($role, $this->userId);
            }
        }
        foreach ($assignedRoles as $assignedRole) {
            $this->authManager->revoke($assignedRole, $this->userId);
        }
        return true;
    }
}