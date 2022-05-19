<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\common;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Item
 * @package lujie\fulfillment\common
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemValue extends Model
{
    /**
     * @var int
     */
    public $valueCent;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $warehouseCode;

    /**
     * @var string
     */
    public $warehouseCountry;
}
