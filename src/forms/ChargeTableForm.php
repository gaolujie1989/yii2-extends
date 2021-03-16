<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;


use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\charging\models\ChargeTable;
use lujie\extend\base\FormTrait;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ChargeTableForm
 *
 * @property int $display_min_limit
 * @property int $display_max_limit
 * @property int $display_per_limit
 * @property int $display_min_over_limit
 * @property int $display_max_over_limit
 *
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableForm extends ChargeTable
{
    use FormTrait;

    /**
     * @var array
     */
    public static $chargeGroups = [];
    /**
     * @var array
     */
    public static $chargeLimitUnits = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = ModelHelper::formRules($this, parent::rules());
        ModelHelper::removeAttributesRules($rules, ['limit_unit']);
        return $rules;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'money' => [
                'class' => MoneyAliasBehavior::class,
                'aliasProperties' => [
                    'price' => 'price_cent',
                    'over_limit_price' => 'over_limit_price_cent',
                ]
            ],
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'discountPercent' => 'additional.discountPercent',
                ],
                'aliasDefaults' => [
                    'discountPercent' => 0,
                ]
            ],
            'unit' => [
                'class' => UnitAliasBehavior::class,
                'baseUnitAttribute' => 'limit_unit',
                'displayUnitAttribute' => 'display_limit_unit',
                'aliasProperties' => [
                    'display_min_limit' => 'min_limit',
                    'display_max_limit' => 'max_limit',
                    'display_per_limit' => 'per_limit',
                    'display_min_over_limit' => 'min_over_limit',
                    'display_max_over_limit' => 'max_over_limit',
                ]
            ],
            'timestampAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'started_time' => 'started_at',
                    'ended_time' => 'ended_at',
                ]
            ]
        ]);
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     * @inheritdoc
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (isset($values['charge_type'])) {
            $chargeType = $values['charge_type'];
            $this->charge_group = static::$chargeGroups[$chargeType] ?? '';
            $this->limit_unit = static::$chargeLimitUnits[$chargeType] ?? '';
        }
        if (isset($values['display_limit_unit'])) {
            $this->display_limit_unit = $values['display_limit_unit'];
        }
        parent::setAttributes($values, $safeOnly);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @inheritdoc
     */
    public function setAttribute($name, $value)
    {
        if ($name === 'charge_type') {
            $this->charge_group = static::$chargeGroups[$value] ?? '';
            $this->limit_unit = static::$chargeLimitUnits[$value] ?? '';
        }
        parent::setAttribute($name, $value);
    }
}
