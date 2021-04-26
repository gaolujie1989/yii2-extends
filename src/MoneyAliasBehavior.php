<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class UnitBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MoneyAliasBehavior extends AliasPropertyBehavior
{
    /**
     * @var int
     */
    public $aliasDefaultValue = 0;

    /**
     * @var int
     */
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
     * @param string $name
     * @return mixed|string
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $value = parent::getAliasProperty($name);
        if (is_numeric($value)) {
            return number_format($value / (10 ** $this->decimalLength), $this->decimalLength, '.', '');
        }
        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        if (!is_numeric($value)) {
            $value = strtr($value, [',' => '.']);
        }
        if (is_numeric($value)) {
            $value = round($value * 10 ** $this->decimalLength);
        } else {
            if ($value) {
                Yii::warning("Money value {$value} is not a number, set with default", __METHOD__);
            }
            $value = $this->aliasDefaultValue;
        }
        parent::setAliasProperty($name, $value);
    }
}
