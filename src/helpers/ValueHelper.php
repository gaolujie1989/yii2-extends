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
     * @throws \Exception
     * @inheritdoc
     */
    public static function isMatch($value, $condition, bool $strict = false): bool
    {
        if (is_array($condition)) {
            return in_array($value, $condition, $strict);
        }
        if (strtolower($condition) === 'empty') {
            return empty($value);
        }
        if (is_string($condition)) {
            $first = $condition[0];
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
            $first = substr($condition, 0, 2);
            if ($first === '>=') {
                $condition = substr($condition, 2);
                return $value >= $condition;
            }
            if ($first === '<=') {
                $condition = substr($condition, 2);
                return $value <= $condition;
            }
            if (strpos($condition, '*') !== false || strpos($condition, '?') !== false) {
                return StringHelper::matchWildcard($condition, $value);
            }
        }
        /** @noinspection TypeUnsafeComparisonInspection */
        return $strict ? $value === $condition : $value == $condition;
    }

    /**
     * @param array|object $data
     * @param array $condition
     * @param bool $strict
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public static function match($data, array $condition, bool $strict = false): bool
    {
        if (isset($condition[0])) {
            $op = strtoupper($condition[0]);
            if ($op === 'AND' || $op === 'OR') {
                $opResult = $op === 'OR';
                array_shift($condition);
                foreach ($condition as $subCondition) {
                    if (static::match($data, $subCondition) === $opResult) {
                        return $opResult;
                    }
                }
                return !$opResult;
            }
            if ($op === 'NOT' && isset($condition[1])) {
                return !static::match($data, $condition[1]);
            }
        }
        foreach ($condition as $key => $item) {
            $value = ArrayHelper::getValue($data, $key);
            if (!static::isMatch($value, $item, $strict)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $a1
     * @param array $a2
     * @return bool
     * @inheritdoc
     */
    public static function isArrayEqual(array $a1, array $a2, bool $strict = true): bool
    {
        if (count($a1) !== count($a2)) {
            return false;
        }
        foreach ($a1 as $k => $v) {
            if (!array_key_exists($k, $a2)) {
                return false;
            }
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($strict ? $a2[$k] !== $v : $a2[$k] != $v) {
                return false;
            }
        }
        return true;
    }
}
