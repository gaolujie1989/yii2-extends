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
    public const UNIT_WEIGHT_G = 'g';
    public const UNIT_WEIGHT_KG = 'kg';
    public const UNIT_MONEY_YUAN = 'yuan';
    public const UNIT_MONEY_CENT = 'cent';

    public const UNIT_SIZE_MM = 'mm';
    public const UNIT_SIZE_CM = 'cm';
    public const UNIT_SIZE_DM = 'dm';
    public const UNIT_SIZE_M = 'm';
    public const UNIT_AREA_MM = 'mm2';
    public const UNIT_AREA_CM = 'cm2';
    public const UNIT_AREA_DM = 'dm2';
    public const UNIT_AREA_M = 'm2';
    public const UNIT_VOLUME_MM = 'mm3';
    public const UNIT_VOLUME_CM = 'cm3';
    public const UNIT_VOLUME_DM = 'dm3';
    public const UNIT_VOLUME_M = 'm3';

    /**
     * @var array
     */
    private static $unitConvertRates = [
        'kg2g' => 1000,
        'g2kg' => 0.001,
        'yuan2cent' => 100,
        'cent2yuan' => 0.01,

        'mm2cm' => 0.1,
        'mm2dm' => 0.01,
        'mm2m' => 0.001,
        'cm2mm' => 10,
        'cm2dm' => 0.1,
        'cm2m' => 0.01,
        'dm2mm' => 100,
        'dm2cm' => 10,
        'dm2m' => 0.1,
        'm2mm' => 1000,
        'm2cm' => 100,
        'm2dm' => 10,

        'mm22cm2' => 0.01,
        'mm22dm2' => 0.0001,
        'mm22m2' => 0.000001,
        'cm22mm2' => 100,
        'cm22dm2' => 0.01,
        'cm22m2' => 0.0001,
        'dm22mm2' => 10000,
        'dm22cm2' => 100,
        'dm22m2' => 0.01,
        'm22mm2' => 1000000,
        'm22cm2' => 10000,
        'm22dm2' => 100,

        'mm32cm3' => 0.001,
        'mm32dm3' => 0.000001,
        'mm32m3' => 0.000000001,
        'cm32mm3' => 1000,
        'cm32dm3' => 0.001,
        'cm32m3' => 0.000001,
        'dm32mm3' => 1000000,
        'dm32cm3' => 1000,
        'dm32m3' => 0.001,
        'm32mm3' => 1000000000,
        'm32cm3' => 1000000,
        'm32dm3' => 1000,
    ];

    private static $onlyIntUnits = ['mm', 'mm2', 'mm3', 'g', 'cent'];

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
            throw new InvalidValueException("Base unit {$this->baseUnit} Or display unit {$this->displayUnit} can not be empty");
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
            return static::convert($value, $this->baseUnit, $this->displayUnit);
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
            $value = static::convert($value, $this->displayUnit, $this->baseUnit);
        } else {
            $value = 0;
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
    public static function convert($value, string $from, string $to)
    {
        $from = strtolower($from);
        $to = strtolower($to);
        if ($from === $to) {
            return $value;
        }
        $key = $from . '2' . $to;
        $x = static::$unitConvertRates[$key] ?? 1;
        $convertedValue = $x >= 1 ? $value * $x : $value / round(1 / $x);
        if (in_array($to, static::$onlyIntUnits, true)) {
            $convertedValue = (int)round($convertedValue);
        }
        return $convertedValue;
    }
}
