<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;


use lujie\charging\models\ChargeTable;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ChargeTableForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableForm extends ChargeTable
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $attributes = [
            'price_cent', 'over_limit_price_cent',
            'limit_unit', 'min_limit', 'max_limit', 'per_limit',
            'min_over_limit', 'max_over_limit',
            'started_at', 'ended_at',
        ];
        ModelHelper::removeAttributesRules($rules, $attributes);
        return $rules;
    }
}