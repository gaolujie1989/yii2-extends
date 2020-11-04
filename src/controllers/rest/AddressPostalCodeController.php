<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address\controllers\rest;

use lujie\common\address\models\AddressPostalCode;
use lujie\extend\rest\ActiveController;

/**
 * Class AddressPostalCodeController
 * @package lujie\common\address\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeController extends ActiveController
{
    public $modelClass = AddressPostalCode::class;
}