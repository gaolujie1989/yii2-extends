<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;

use yii\base\Behavior;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class AliasAttributeBehavior
 *
 * @property Model $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AliasPropertyBehavior extends Behavior
{
    /**
     * [
     *      'aliasAttr' => 'realAttr'
     * ]
     * @var array
     */
    public $aliasProperties = [];

    /**
     * @var array
     */
    public $aliasDefaults = [];

    /**
     * @var mixed
     */
    public $aliasDefaultValue = '';

    #region overwrite for magic get/set

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     * @throws \Exception
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->isAliasProperty($name)) {
            return $this->getAliasProperty($name);
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value): void
    {
        if ($this->isAliasProperty($name)) {
            $this->setAliasProperty($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true): bool
    {
        if ($this->isAliasProperty($name)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true): bool
    {
        if ($this->isAliasProperty($name)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    #endregion

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function isAliasProperty(string $name): bool
    {
        return isset($this->aliasProperties[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $property = $this->aliasProperties[$name];
        return ArrayHelper::getValue($this->owner, $property, $this->aliasDefaults[$name] ?? $this->aliasDefaultValue);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        $property = $this->aliasProperties[$name];
        if (($pos = strpos($property, '.')) === false) {
            $this->owner->$property = $value;
        } else {
            $attribute = substr($property, 0, $pos);
            $attributeValue = $this->owner->$attribute ?: [];
            ArrayHelper::setValue($attributeValue, substr($property, $pos + 1), $value);
            $this->owner->$attribute = $attributeValue;
        }
    }
}
