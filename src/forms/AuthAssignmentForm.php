<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\BaseManager;

/**
 * Class AuthAssignmentForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthAssignmentForm extends Model
{
    /**
     * @var int
     */
    public $userId;

    /**
     * 理论上不应该直接给特定用户直接指派权限，会造成后期权限管理混乱
     * @var array
     */
    public $roles;

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
            [['roles'], 'each', 'rule' => ['string']],
        ];
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function saveAssignments(): void
    {
        if (!is_array($this->roles)) {
            return;
        }
        if (empty($this->roles)) {
            $this->authManager->revokeAll($this->userId);
            return;
        }
        $assignedRoles = $this->authManager->getRolesByUser($this->userId);
        $assignedRoles = ArrayHelper::index($assignedRoles, 'name');
        foreach ($this->roles as $roleName) {
            if (isset($assignedRoles[$roleName])) {
                unset($assignedRoles[$roleName]);
            } else if ($role = $this->authManager->getRole($roleName)) {
                $this->authManager->assign($role, $this->userId);
            }
        }
        foreach ($assignedRoles as $assignedRole) {
            $this->authManager->revoke($assignedRole, $this->userId);
        }
    }
}