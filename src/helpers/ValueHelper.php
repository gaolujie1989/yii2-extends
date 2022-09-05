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
            $v2 = $a2[$k];
            if (is_array($v) && is_array($v2)) {
                if (static::isArrayEqual($v, $v2)) {
                    continue;
                }
                return false;
            }
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($strict ? $v2 !== $v : $a2[$k] != $v) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $str
     * @param string $splitPattern
     * @return array
     * @inheritdoc
     */
    public static function strToArray(string $str, string $splitPattern = '/[,;\s]/'): array
    {
        $values = preg_split($splitPattern, $str, -1, PREG_SPLIT_NO_EMPTY);
        return array_filter(array_map('trim', $values), [static::class, 'notEmpty']);
    }

    /**
     * @param int|string $dateTime
     * @param string|null $format
     * @return int|string
     * @inheritdoc
     */
    public static function formatDateTime(int|string $dateTime, ?string $format = null): int|string
    {
        $timestamp = is_int($dateTime) ? $dateTime : strtotime($dateTime);
        if (strlen($timestamp) > 10) {
            $timestamp = substr($timestamp, 0, 10);
        }
        if ($format === null) {
            return $timestamp;
        }
        return date($format, $timestamp);
    }

    /**
     * @param array $array
     * @param array|string[] $childrenKeys
     * @param string $sortKey
     * @param bool $asc
     * @return array
     * @inheritdoc
     */
    public static function sort(array $array, array $childrenKeys = ['items'], string $sortKey = 'sort', bool $asc = true): array
    {
        uasort($array, static function ($a, $b) use ($sortKey, $asc) {
            $sortA = $a[$sortKey] ?? 0;
            $sortB = $b[$sortKey] ?? 0;
            return $asc ? $sortA <=> $sortB : $sortB <=> $sortA;
        });

        $childrenKey = count($childrenKeys) > 1 ? array_shift($childrenKeys) : reset($childrenKeys);
        foreach ($array as $key => $childArray) {
            if (isset($childArray[$childrenKey])) {
                $array[$key][$childrenKey] = static::sort($childArray[$childrenKey], $childrenKeys, $sortKey);
            }
        }

        return $array;
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     * @inheritdoc
     */
    public static function fromRange(string $from, string $to): array
    {
        $fromLen = strlen($from);
        $toLen = strlen($to);
        if ($fromLen !== $toLen) {
            return [];
        }
        $prefix = '';
        $suffix = '';
        for ($i = $fromLen; $i > 0; $i--) {
            if (strpos($from, substr($to, 0, $i)) === 0) {
                $prefix = substr($from, 0, $i);
                break;
            }
        }
        for ($i = $fromLen; $i > 0; $i--) {
            if (substr($from, -$i) === substr($to, -$i)) {
                $suffix = substr($from, -$i);
                break;
            }
        }

        $prefixLen = strlen($prefix);
        $suffixLen = strlen($suffix);
        $valueLength = $fromLen - $prefixLen - $suffixLen;
        if ($valueLength < 0) {
            return [$fromLen];
        }

        $rangeValues = [];
        $fromValue = substr($from, $prefixLen, $valueLength);
        $toValue = substr($to, $prefixLen, $valueLength);
        for ($v = $fromValue; $v <= $toValue; $v++) {
            $rangeValues[] = $prefix . str_pad($v, $valueLength, '0', STR_PAD_LEFT) . $suffix;
        }
        return $rangeValues;
    }
}
