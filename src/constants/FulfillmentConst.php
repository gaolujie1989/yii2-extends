<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\constants;


class FulfillmentConst
{
    public const FULFILLMENT_TYPE_AMAZON = 'AMAZON';
    public const FULFILLMENT_TYPE_PM = 'PM';

    public const FULFILLMENT_ORDER_STATUS_PENDING = 0;
    public const FULFILLMENT_ORDER_STATUS_PUSHED = 1;
    public const FULFILLMENT_ORDER_STATUS_PICKING = 5;
    public const FULFILLMENT_ORDER_STATUS_SHIPPED = 10;
    public const FULFILLMENT_ORDER_STATUS_CANCELLED = 11;
}
