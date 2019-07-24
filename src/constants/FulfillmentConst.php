<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\constants;


class FulfillmentConst
{
    public const ACCOUNT_TYPE_AMAZON = 'AMAZON';
    public const ACCOUNT_TYPE_PM = 'PM';

    public const ORDER_STATUS_PENDING = 0;
    public const ORDER_STATUS_PUSHING = 1;
    public const ORDER_STATUS_PUSHED = 2;
    public const ORDER_STATUS_PICKING = 5;
    public const ORDER_STATUS_SHIPPED = 10;
    public const ORDER_STATUS_CANCELLED = 11;
    public const ORDER_STATUS_PUSH_FAILED = 12;
}
