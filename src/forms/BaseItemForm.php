<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * Class AuthItemForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseItemForm extends AuthForm
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $ruleName;

    /**
     * @var Permission|Role|null|Item
     */
    private $_item;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['ruleName'], 'string', 'max' => 64],
            [['ruleName'], 'validateRuleNameExist'],
            [['name'], 'validateNameNotExist', 'when' => function () {
                return $this->_item === null;
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateRuleNameExist(): void
    {
        if ($this->ruleName && $this->authManager->getRule($this->ruleName) === null) {
            $this->addError('ruleName', 'Rule not exist');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateNameNotExist(): void
    {
        if ($this->_item && $this->_item->name === $this->name) {
            return;
        }
        if ($this->authManager->getPermission($this->name) || $this->authManager->getRole($this->name)) {
            $this->addError('name', 'Name already exist');
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $item = $this->createItem();
        $item->description = $this->description;
        $item->ruleName = $this->ruleName;
        return $this->_item
            ? $this->authManager->update($this->_item->name, $item)
            : $this->authManager->add($item);
    }

    /**
     * @return Role|Permission
     * @inheritdoc
     */
    abstract protected function createItem(): Item;

    /**
     * @param Item $item
     * @inheritdoc
     */
    public function setItem(Item $item): void
    {
        $this->_item = $item;
    }

    /**
     * @param string $itemName
     * @return BaseItemForm|null
     * @inheritdoc
     */
    public static function findOne(string $itemName): ?self
    {
        $itemForm = new static();
        $item = $itemForm->authManager->getPermission($itemName) ?: $itemForm->authManager->getRole($itemName);
        if ($item) {
            $itemForm->setItem($item);
            return $itemForm;
        }
        return null;
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function delete(): bool
    {
        if ($this->_item) {
            return $this->authManager->remove($this->_item);
        }
        return false;
    }
}
