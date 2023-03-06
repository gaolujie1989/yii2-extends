<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\charging\models\ChargeTable;
use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ChargeTableForm
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
        $rules = $this->formRules();
        ModelHelper::removeAttributesRules($rules, ['limit_unit']);
        return $rules;
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     * @inheritdoc
     */
    public function setAttributes($values, $safeOnly = true): void
    {
        if (isset($values['charge_type'])) {
            $chargeType = $values['charge_type'];
            $this->charge_group = static::$chargeGroups[$chargeType] ?? '';
            $this->limit_unit = static::$chargeLimitUnits[$chargeType] ?? $values['display_limit_unit'];
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
    public function setAttribute($name, $value): void
    {
        if ($name === 'charge_type') {
            $this->charge_group = static::$chargeGroups[$value] ?? '';
            $this->limit_unit = static::$chargeLimitUnits[$value] ?? '';
        }
        parent::setAttribute($name, $value);
    }
}
