<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;

/**
 * Class MockHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockHelper
{
    /**
     * @param array $rules
     * @param bool $valid
     * @return array
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public static function mockData(array $rules, bool $valid = true, array $config = []): array
    {
        $mockValues = [];
        foreach ($rules as $rule) {
            $attributes = (array)$rule[0];
            $type = $rule[1];
            $typeValues = [];
            foreach ($attributes as $attribute) {
                $typeValues[$attribute] = static::mockValue($type, $rule, $valid, $config);
            }
            //For date/time values, mock value increment by attribute sort
            if (in_array($type, ['date', 'datetime', 'time'], true)) {
                $typeAttributes = array_keys($typeValues);
                sort($typeValues);
                $typeValues = array_combine($typeAttributes, $typeValues);
            }
            $mockValues[] = $typeValues;
        }
        return array_merge(...$mockValues);
    }

    /**
     * @param string $type
     * @param array $rule
     * @param bool $valid
     * @return false|float|int|mixed|string|null
     * @throws \yii\base\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public static function mockValue(string $type, array $rule, bool $valid = true, array $config = [])
    {
        if ($valid) {
            switch ($type) {
                case 'boolean':
                    return random_int(0, 1);
                case 'date':
                case 'datetime':
                case 'time':
                    $ts = random_int(strtotime('2020-01-01'), strtotime('2022-01-01'));
                    return date($config['dateFormat'] ?? 'c', $ts);
                case 'double':
                case 'number':
                    $x = 10 ** ($config['decimalLength'] ?? 2);
                    return random_int($rule['min'] ?? 0, ($rule['max'] ?? 999) * $x) / $x;
                case 'integer':
                    return random_int($rule['min'] ?? 0, $rule['max'] ?? 999);
                case 'in':
                    return $rule['range'][array_rand($rule['range'])];
                case 'string':
                    $length = $rule['max'] ?? 50;
                    return Yii::$app->getSecurity()->generateRandomString($length);
                case 'each':
                    $eachRule = $rule['rule'];
                    return static::mockValue($eachRule[0], $eachRule, $valid);
                default:
                    return null;
            }
        } else {
            switch ($type) {
                case 'boolean':
                    return -1;
                case 'date':
                case 'datetime':
                case 'time':
                    return '2021-20-21 XX';
                case 'double':
                case 'number':
                    return '12.3B';
                case 'integer':
                    return '12B';
                case 'in':
                    return max($rule['range']) . '1';
                case 'string':
                    $length = $rule['max'] ?? 50;
                    return Yii::$app->getSecurity()->generateRandomString($length + 1);
                case 'each':
                    $eachRule = $rule['rule'];
                    return static::mockValue($eachRule[0], $eachRule, $valid);
                default:
                    return null;
            }
        }
    }
}
