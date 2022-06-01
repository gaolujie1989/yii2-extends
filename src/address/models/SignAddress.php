<?php

namespace lujie\common\address\models;

/**
 * Class SignAddress
 * @package lujie\common\address\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SignAddress extends Address
{
    /**
     * @var bool
     */
    public $identifyBySignature = true;
}
