<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\batch\BatchForm;

/**
 * Class ShippingZoneBatchForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneBatchForm extends BatchForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return [
            'price', 'weight_kg_limit',
            'length_cm_limit', 'width_cm_limit', 'height_cm_limit',
            'l2wh_cm_limit', 'lwh_cm_limit', 'lh_cm_limit',
            'volume_l_limit',
            'started_time', 'ended_time'
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['price', 'weight_kg_limit',
                'length_cm_limit', 'width_cm_limit', 'height_cm_limit',
                'l2wh_cm_limit', 'lwh_cm_limit', 'lh_cm_limit',
                'volume_l_limit'], 'number'],
            [['started_time', 'ended_time'], 'string'],
        ];
    }
}
