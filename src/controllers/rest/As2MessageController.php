<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\controllers\rest;

use lujie\as2\models\As2Message;
use lujie\extend\rest\ActiveController;

/**
 * Class As2MessageController
 * @package lujie\as2\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2MessageController extends ActiveController
{
    public $modelClass = As2Message::class;
}