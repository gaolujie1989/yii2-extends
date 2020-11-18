<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\common;


use yii\base\Model;

/**
 * Class Order
 * @package lujie\fulfillment\common
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Order extends Model
{
    /**
     * @var int
     */
    public $orderId;

    /**
     * @var string
     */
    public $orderNo;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var OrderItem[]
     */
    public $orderItems;

    /**
     * @var array
     */
    public $additional = [];
}
