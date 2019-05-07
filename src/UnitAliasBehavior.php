<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;


use yii\base\Behavior;
use yii\base\InvalidValueException;

/**
 * Class UnitBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UnitAliasBehavior extends AliasPropertyBehavior
{
    const UNIT_SIZE_MM = 'mm';
    const UNIT_SIZE_CM = 'cm';
    const UNIT_SIZE_M = 'm';
    const UNIT_WEIGHT_G = 'g';
    const UNIT_WIGHT_KG = 'kg';
    const UNIT_MONEY_YUAN = 'yuan';
    const UNIT_MONEY_CENT = 'cent';

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
    public function initUnit()
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
     * @param $name
     * @return float|int|mixed
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $this->initUnit();
        $value = parent::getAliasProperty($name);
        return $this->convert($value, $this->baseUnit, $this->displayUnit);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @inheritdoc
     */
    public function setAliasProperty($name, $value)
    {
        $this->initUnit();
        $value = $this->convert($value, $this->displayUnit, $this->baseUnit);
        return parent::setAliasProperty($name, $value);
    }

    /**
     * @param $value
     * @param $from
     * @param $to
     * @return float|int
     * @inheritdoc
     */
    public function convert($value, $from, $to)
    {
        if ($from == $to) {
            return $value;
        }
        $key = $from . '2' . $to;
        $convertedValue = $value * (static::$unitConvertRates[$key] ?? 1);
        if (in_array($to, static::$onlyIntUnits)) {
            $convertedValue = round($convertedValue);
        }
        return $convertedValue;
    }
}
