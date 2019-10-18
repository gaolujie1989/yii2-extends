<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;


use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;

/**
 * Class UnitBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MoneyAliasBehavior extends AliasPropertyBehavior
{
    public $decimalLength = 2;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!is_int($this->decimalLength) || $this->decimalLength < 1 || $this->decimalLength > 6) {
            throw new InvalidConfigException('The property "decimalLength" must be int and between 1 and 6');
        }
    }

    /**
     * @param $name
     * @return float|int|mixed
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $value = parent::getAliasProperty($name);
        return $value / (10 ** $this->decimalLength);
    }

    /**
     * @param $name
     * @param $value
     * @inheritdoc
     */
    public function setAliasProperty($name, $value): void
    {
        if (!is_numeric($value)) {
            $value = strtr($value, [',' => '.']);
            if (!is_numeric($value)) {
                throw new InvalidArgumentException('Money value must be a number');
            }
        }
        $value = round($value * 10 ** $this->decimalLength);
        parent::setAliasProperty($name, $value);
    }
}
