<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * Class AuthAssignmentForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AssignmentForm extends AuthForm
{
    public const SCENARIO_ASSIGN = 'ASSIGN';
    public const SCENARIO_REVOKE = 'REVOKE';

    /**
     * @var IdentityInterface
     */
    public $userClass;

    /**
     * @var int|string
     */
    public $userId;

    /**
     * @var string[]
     */
    public $itemNames;

    /**
     * @var Permission[]|Role[]
     */
    private $_items;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['itemNames', 'userId'], 'required'],
            [['userId'], 'validateUserIdExist'],
            [['userId'], 'string', 'max' => 64],
            [['itemNames'], 'validateItemNamesExist'],
            [['itemNames'], 'validateUserIdItemNamesNotAssigned', 'on' => static::SCENARIO_ASSIGN],
            [['itemNames'], 'validateUserIdItemNamesAssigned', 'on' => static::SCENARIO_REVOKE],
        ];
    }

    /**
     * @param string $itemName
     * @return Item|null|Permission|Role
     * @inheritdoc
     */
    protected function getItem(string $itemName): ?Item
    {
        if (empty($this->_items[$itemName])) {
            $this->_items[$itemName] = $this->authManager->getRole($itemName) ?: $this->authManager->getPermission($itemName);
        }
        return $this->_items[$itemName];
    }

    /**
     * @inheritdoc
     */
    public function validateUserIdExist(): void
    {
        if ($this->userClass && $this->userClass::findIdentity($this->userId) === null) {
            $this->addError('userId', 'User ID not exist');
        }
        //to avoid string validator error
        if (is_int($this->userId)) {
            $this->userId = (string)$this->userId;
        }
    }

    /**
     * @inheritdoc
     */
    public function validateItemNamesExist(): void
    {
        $notExistItemNames = [];
        foreach ($this->itemNames as $itemName) {
            if ($this->getItem($itemName) === null) {
                $notExistItemNames[] = $itemName;
            }
        }
        if ($notExistItemNames) {
            $message = strtr('ItemNames {itemNames} not exist.', [
                '{itemNames}' => implode(',', $notExistItemNames)
            ]);
            $this->addError('itemNames', $message);
        }
    }

    /**
     * @inheritdoc
     */
    public function validateUserIdItemNamesNotAssigned(): void
    {
        $assignedItemNames = [];
        foreach ($this->itemNames as $itemName) {
            if ($this->authManager->getAssignment($itemName, $this->userId) !== null) {
                $assignedItemNames[] = $itemName;
            }
        }
        if ($assignedItemNames) {
            $message = strtr('ItemNames {itemNames} already assigned.', [
                '{itemNames}' => implode(',', $assignedItemNames)
            ]);
            $this->addError('itemNames', $message);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function assign(): bool
    {
        $this->setScenario(static::SCENARIO_ASSIGN);
        if (!$this->validate()) {
            return false;
        }
        foreach ($this->itemNames as $itemName) {
            $this->authManager->assign($this->getItem($itemName), $this->userId);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validateUserIdItemNamesAssigned(): void
    {
        $notAssignedItemNames = [];
        foreach ($this->itemNames as $itemName) {
            if ($this->authManager->getAssignment($itemName, $this->userId) === null) {
                $notAssignedItemNames[] = $itemName;
            }
        }
        if ($notAssignedItemNames) {
            $message = strtr('ItemNames {itemNames} not assigned.', [
                '{itemNames}' => implode(',', $notAssignedItemNames)
            ]);
            $this->addError('itemNames', $message);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function revoke(): bool
    {
        $this->setScenario(static::SCENARIO_REVOKE);
        if (!$this->validate()) {
            return false;
        }
        foreach ($this->itemNames as $itemName) {
            $this->authManager->revoke($this->getItem($itemName), $this->userId);
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function save(): bool
    {
        $this->setScenario(static::SCENARIO_DEFAULT);
        if (!$this->validate()) {
            return false;
        }
        $assignments = $this->authManager->getAssignments($this->userId);
        $assignedItemNames = ArrayHelper::getColumn($assignments, 'roleName');
        $toAssignItemNames = array_diff($this->itemNames, $assignedItemNames);
        $toRevokeItemNames = array_diff($assignedItemNames, $this->itemNames);
        foreach ($toAssignItemNames as $itemName) {
            $this->authManager->assign($this->getItem($itemName), $this->userId);
        }
        foreach ($toRevokeItemNames as $itemName) {
            $this->authManager->revoke($this->getItem($itemName), $this->userId);
        }
        return true;
    }
}
