<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class ValueHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ValueHelper
{
    /**
     * @param mixed $value
     * @return bool
     * @inheritdoc
     */
    public static function isEmpty($value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '') || (is_array($value) && count($value) === 0);
    }

    /**
     * @param mixed $value
     * @return bool
     * @inheritdoc
     */
    public static function notEmpty($value): bool
    {
        return !self::isEmpty($value);
    }

    /**
     * check value is match condition or not
     * @param mixed $value
     * @param string|array|mixed $condition
     * @param bool $strict
     * @return bool
     * @inheritdoc
     */
    public static function isMatch($value, $condition, bool $strict = false): bool
    {
        if (is_array($condition)) {
            return in_array($value, $condition, $strict);
        }
        $first = substr($condition, 0, 1);
        if ($first === '!') {
            $condition = substr($condition, 1);
            return !self::isMatch($value, $condition, $strict);
        }
        if ($first === '>') {
            $condition = substr($condition, 1);
            return $value > $condition;
        }
        if ($first === '<') {
            $condition = substr($condition, 1);
            return $value < $condition;
        }
        if (strpos($condition, '*') !== false || strpos($condition, '?') !== false) {
            return StringHelper::matchWildcard($condition, $value);
        }
        return $strict ? $value === $condition : $value == $condition;
    }

    /**
     * @param $data
     * @param array $conditions
     * @param bool $strict
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public static function matchAll($data, array $conditions, bool $strict = false): bool
    {
        foreach ($conditions as $key => $item) {
            $value = ArrayHelper::getValue($data, $key);
            if (!static::isMatch($value, $item, $strict)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $data
     * @param array $conditions
     * @param bool $strict
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public static function matchOne($data, array $conditions, bool $strict = false): bool
    {
        foreach ($conditions as $key => $item) {
            $value = ArrayHelper::getValue($data, $key);
            if (static::isMatch($value, $item, $strict)) {
                return true;
            }
        }
        return false;
    }
}
