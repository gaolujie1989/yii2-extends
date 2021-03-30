<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\base\InvalidValueException;

class RandomHelper
{
    /**
     * @param array $items
     * @param int $flag
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public static function randomByProbability(array $items, $flag = 1): string
    {
        $random = random_int(0, array_sum($items) * $flag);
        foreach ($items as $key => $probability) {
            if ($random <= $probability * $flag) {
                return $key;
            }
            $random -= $probability * $flag;
        }
        throw new InvalidValueException('Invalid Random Value');
    }
}
