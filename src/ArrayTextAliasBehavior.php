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
    /**
     * @var array
     */
    public $separators = [',', ';', '/'];

    /**
     * @var bool
     */
    public $trim = true;

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
        $separator = reset($this->separators);
        return $value ? implode($separator, $value) : '';
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
            $value = [$value];
            foreach ($this->separators as $separator) {
                if (empty($value)) {
                    break;
                }
                $value = array_map(static function($v) use ($separator) {
                    return explode($separator, $v);
                }, $value);
                if ($this->trim) {
                    $value = array_filter(array_map('trim', array_merge(...$value)));
                } else {
                    $value = array_filter(array_merge(...$value));
                }
            }
        }
        parent::setAliasProperty($name, $value);
    }
}
