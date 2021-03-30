<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\forms;

use lujie\batch\BatchForm;

/**
 * Class SalesChannelOrderBatchForm
 * @package lujie\sales\channel\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderBatchForm extends BatchForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['sales_channel_status'];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['sales_channel_status'], 'safe']
        ];
    }
}
