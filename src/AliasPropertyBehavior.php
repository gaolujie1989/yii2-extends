<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;

use yii\base\Behavior;

/**
 * Class AliasAttributeBehavior
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

    #region overwrite for magic get/set

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->isAliasProperty($name)) {
            $this->getAliasProperty($name);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
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
    public function canSetProperty($name, $checkVars = true)
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
    public function canGetProperty($name, $checkVars = true)
    {
        if ($this->isAliasProperty($name)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    #endregion

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    public function isAliasProperty($name)
    {
        return isset($this->aliasProperties[$name]);
    }

    /**
     * @param $name
     * @return mixed
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $property = $this->aliasProperties[$name];
        return $this->owner->$property;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @inheritdoc
     */
    public function setAliasProperty($name, $value)
    {
        $property = $this->aliasProperties[$name];
        return $this->owner->$property = $value;
    }
}
