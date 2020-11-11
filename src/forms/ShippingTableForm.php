<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;


use lujie\charging\models\ShippingTable;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ShippingTableForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableForm extends ShippingTable
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $attributes = [
            'price_cent', 'weight_g_limit', 'volume_mm3_limit',
            'length_mm_limit', 'width_mm_limit', 'height_mm_limit',
            'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit',
            'started_at', 'ended_at',
        ];
        ModelHelper::removeAttributesRules($rules, $attributes);
        return array_merge($rules, [
            [['price', 'weight_kg_limit', 'volume_l_limit',
                'length_cm_limit', 'width_cm_limit', 'height_cm_limit',
                'l2wh_cm_limit', 'lwh_cm_limit', 'lh_cm_limit',], 'number'],
            [['started_time', 'ended_time'], 'safe']
        ]);
    }
}