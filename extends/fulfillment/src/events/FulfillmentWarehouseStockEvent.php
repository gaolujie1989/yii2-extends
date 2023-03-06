<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\events;

use lujie\fulfillment\models\FulfillmentOrder;
use yii\base\Event;

/**
 * Class FulfillmentEvent
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockEvent extends Event
{
    /**
     * @var array
     */
    public $fulfillmentWarehouseStocks = [];
}
