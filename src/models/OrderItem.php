<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\models;

use yii\base\Model;

/**
 * Class OrderItem
 * @package lujie\extend\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OrderItem extends Model
{
    /**
     * @var int
     */
    public $itemId;

    /**
     * @var string
     */
    public $itemNo;

    /**
     * @var string
     */
    public $orderItemName;

    /**
     * @var int
     */
    public $orderedQty;

    /**
     * @var Item
     */
    public $item;
}