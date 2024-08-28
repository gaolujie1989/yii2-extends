<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use Yii;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\BaseManager;
use yii\rbac\ManagerInterface;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * Class AuthAssignmentForm
 *
 * @property string[] $roles
 *
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
    public $_roleNames;

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
        $this->authManager = Instance::ensure($this->authManager, ManagerInterface::class);
        if (empty($this->userClass)) {
            $this->userClass = Yii::$app->getUser()->identityClass;
        }
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
     * @return bool
     * @inheritdoc
     */
    public function validateRoles(): bool
    {
        $roleNames = $this->_roleNames;
        $invalidRoleNames = [];
        $validRoles = [];
        $defaultRoles = $this->authManager->getDefaultRoles();
        $roleNames = array_diff($roleNames, $defaultRoles);
        foreach ($roleNames as $roleName) {
            $role = $this->authManager->getRole($roleName);
            if ($role === null) {
                $invalidRoleNames[] = $roleName;
            } else {
                $validRoles[$roleName] = $role;
            }
        }
        if ($invalidRoleNames) {
            $this->addError('roles', 'Invalid roles:' . implode(',', $invalidRoleNames));
            return false;
        }
        $this->_roles = $validRoles;
        return true;
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
        $roles = $this->_roles;
        if (empty($roles)) {
            $this->authManager->revokeAll($this->userId);
            return true;
        }
        $assignedRoles = $this->authManager->getRolesByUser($this->userId);
        $assignedRoles = ArrayHelper::index($assignedRoles, 'name');
        foreach ($roles as $roleName => $role) {
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

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'roles' => 'roles',
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getRoles(): array
    {
        if (empty($this->userId)) {
            return [];
        }
        if ($this->_roleNames === null) {
            $roles = $this->authManager->getRolesByUser($this->userId);
            $this->_roleNames = array_values(ArrayHelper::getColumn($roles, 'name'));
        }
        return $this->_roleNames;
    }

    /**
     * @param array $roles
     * @inheritdoc
     */
    public function setRoles(array $roles): void
    {
        $this->_roleNames = $roles;
    }
}
