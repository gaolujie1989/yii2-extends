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
    public const FULFILLMENT_STATUS_PROCESSING = 20;
    public const FULFILLMENT_STATUS_HOLDING = 30;
    public const FULFILLMENT_STATUS_PICKING = 50;
    public const FULFILLMENT_STATUS_SHIP_PENDING = 60;
    public const FULFILLMENT_STATUS_SHIPPED = 100;
    public const FULFILLMENT_STATUS_CANCELLED = 110;
    public const FULFILLMENT_STATUS_TO_SHIPPING = 210;
    public const FULFILLMENT_STATUS_TO_HOLDING = 220;
    public const FULFILLMENT_STATUS_TO_CANCELLING = 230;
}
