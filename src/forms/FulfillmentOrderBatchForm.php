<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\forms;

use lujie\batch\BatchForm;

/**
 * Class FulfillmentOrderBatchForm
 * @package lujie\fulfillment\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentOrderBatchForm extends BatchForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['fulfillment_status'];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['fulfillment_status'], 'safe']
        ];
    }
}
