<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\constants;


class FulfillmentConst
{
    public const ACCOUNT_TYPE_AMAZON = 'AMAZON';
    public const ACCOUNT_TYPE_PM = 'PM';

    public const FULFILLMENT_STATUS_PENDING = 0;
    public const FULFILLMENT_STATUS_PUSHING = 10;
    public const FULFILLMENT_STATUS_PUSHED = 20;
    public const FULFILLMENT_STATUS_PICKING = 50;
    public const FULFILLMENT_STATUS_PICKING_CANCELLING = 55;
    public const FULFILLMENT_STATUS_SHIPPED = 100;
    public const FULFILLMENT_STATUS_CANCELLED = 110;
    public const FULFILLMENT_STATUS_PUSH_FAILED = 120;
    public const FULFILLMENT_STATUS_CANCEL_FAILED = 130;
}
