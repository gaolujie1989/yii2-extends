<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\batch\BatchForm;

/**
 * Class ChargeTableBatchForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableBatchForm extends BatchForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return [
            'price', 'over_limit_price', 'discountPercent',
            'display_min_limit', 'display_max_limit', 'display_per_limit',
            'display_min_over_limit', 'display_max_over_limit',
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
            [['price', 'over_limit_price', 'discountPercent',
                'display_min_limit', 'display_max_limit', 'display_per_limit',
                'display_min_over_limit', 'display_max_over_limit'], 'number'],
            [['started_time', 'ended_time'], 'string'],
        ];
    }
}
