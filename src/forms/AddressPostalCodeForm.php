<?php

namespace lujie\common\address\forms;

use lujie\common\address\models\AddressPostalCode;
use lujie\extend\constants\StatusConst;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeForm extends AddressPostalCode
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['status'], 'in', 'range' => StatusConst::STATUS_LIST],
        ]);
    }
}
