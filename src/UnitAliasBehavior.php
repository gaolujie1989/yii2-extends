<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;


use yii\base\InvalidValueException;

/**
 * Class UnitBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UnitAliasBehavior extends AliasPropertyBehavior
{
    public const UNIT_SIZE_MM = 'mm';
    public const UNIT_SIZE_CM = 'cm';
    public const UNIT_SIZE_M = 'm';
    public const UNIT_WEIGHT_G = 'g';
    public const UNIT_WEIGHT_KG = 'kg';
    public const UNIT_MONEY_YUAN = 'yuan';
    public const UNIT_MONEY_CENT = 'cent';

    /**
     * @var array
     */
    private static $unitConvertRates = [
        'kg2g' => 1000,
        'g2kg' => 0.001,
        'cm2mm' => 10,
        'mm2cm' => 0.1,
        'm2mm' => 1000,
        'mm2m' => 0.001,
        'm2cm' => 100,
        'cm2m' => 0.01,
        'yuan2cent' => 100,
        'cent2yuan' => 0.01,
    ];

    private static $onlyIntUnits = ['mm', 'g', 'cent'];

    /**
     * @var string
     */
    public $baseUnit;

    /**
     * @var string
     */
    public $displayUnit;

    /**
     * for changed unit attribute by user,
     * normally base unit should be set by developer,
     * display unit can be set by user
     * @var string
     */
    public $baseUnitAttribute;

    /**
     * for changed unit attribute by user
     * @var string
     */
    public $displayUnitAttribute;

    /**
     * @inheritdoc
     */
    public function initUnit(): void
    {
        if ($this->baseUnitAttribute) {
            $this->baseUnit = $this->owner->{$this->baseUnitAttribute};
        }
        if ($this->displayUnitAttribute) {
            $this->displayUnit = $this->owner->{$this->displayUnitAttribute};
        }
        if (empty($this->baseUnit) || empty($this->displayUnit)) {
            throw new InvalidValueException('Base unit and display unit can not be empty');
        }
    }

    /**
     * @param string $name
     * @return float|int|mixed
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $this->initUnit();
        $value = parent::getAliasProperty($name);
        if (is_numeric($value)) {
            return $this->convert($value, $this->baseUnit, $this->displayUnit);
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
        $this->initUnit();
        if (!is_numeric($value)) {
            $value = strtr($value, [',' => '.']);
        }
        if (is_numeric($value)) {
            $value = $this->convert($value, $this->displayUnit, $this->baseUnit);
        }
        parent::setAliasProperty($name, $value);
    }

    /**
     * @param float|int $value
     * @param string $from
     * @param string $to
     * @return float|int
     * @inheritdoc
     */
    public function convert($value, string $from, string $to)
    {
        if ($from === $to) {
            return $value;
        }
        $key = $from . '2' . $to;
        $convertedValue = $value * (static::$unitConvertRates[$key] ?? 1);
        if (in_array($to, static::$onlyIntUnits, true)) {
            $convertedValue = round($convertedValue);
        }
        return $convertedValue;
    }
}
