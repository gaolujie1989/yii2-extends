<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

/**
 * Trait ModelAttributeTrait
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ModelAttributeTrait
{
    /**
     * @var array attribute values indexed by attribute names
     */
    private $attributes = [];

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]) || in_array($name, $this->attributes(), true);
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name]) || array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        if ($this->hasAttribute($name)) {
            return null;
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
}
