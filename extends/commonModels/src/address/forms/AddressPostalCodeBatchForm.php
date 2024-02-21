<?php

namespace lujie\common\address\forms;

use lujie\batch\BatchForm;
use lujie\extend\constants\StatusConst;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeBatchForm extends BatchForm
{
    /**
     * @var int
     */
    public $status;

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['status', 'note'];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['status'], 'required'],
            [['status'], 'in', 'range' => StatusConst::STATUS_LIST],
            [['note'], 'string'],
        ];
    }
}
