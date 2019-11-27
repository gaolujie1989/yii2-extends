<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\alias\behaviors;

/**
 * Class ArrayTextAliasBehavior
 * @package lujie\alias\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayTextAliasBehavior extends AliasPropertyBehavior
{
    public $separator = ',';

    /**
     * @param $name
     * @return int|mixed|string
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $value = parent::getAliasProperty($name);
        if (is_string($value)) {
            return $value;
        }
        return $value ? implode($this->separator, $value) : '';
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     * @inheritdoc
     */
    public function setAliasProperty($name, $value): void
    {
        if (is_string($value)) {
            $value = $value ? explode($this->separator, $value) : [];
        }
        parent::setAliasProperty($name, $value);
    }
}
