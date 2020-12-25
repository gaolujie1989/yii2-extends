<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\constants;


class FulfillmentConst
{
    public const ACCOUNT_TYPE_AMAZON = 'AMAZON';
    public const ACCOUNT_TYPE_PM = 'PM';
    public const ACCOUNT_TYPE_F4PX = 'F4PX';

    public const FULFILLMENT_TYPE_INBOUND = 'INBOUND';
    public const FULFILLMENT_TYPE_SHIPPING = 'SHIPPING';

    public const FULFILLMENT_STATUS_PENDING = 0;
    public const FULFILLMENT_STATUS_PROCESSING = 20;
    public const FULFILLMENT_STATUS_HOLDING = 30;
    public const FULFILLMENT_STATUS_PICKING = 50;
    public const FULFILLMENT_STATUS_SHIP_ERROR = 80;
    public const FULFILLMENT_STATUS_SHIPPED = 100;
    public const FULFILLMENT_STATUS_CANCELLED = 110;
    public const FULFILLMENT_STATUS_TO_CANCELLING = 210;
    public const FULFILLMENT_STATUS_TO_HOLDING = 220;
    public const FULFILLMENT_STATUS_TO_SHIPPING = 230;

    public const MOVEMENT_TYPE_INBOUND = 'INBOUND';
    public const MOVEMENT_TYPE_OUTBOUND = 'OUTBOUND';
    public const MOVEMENT_TYPE_CORRECTION = 'CORRECTION';

    public const INBOUND_STATUS_PENDING = 0;
    public const INBOUND_STATUS_PROCESSING = 10;
    public const INBOUND_STATUS_SHIPPED = 20;
    public const INBOUND_STATUS_ARRIVED = 50;
    public const INBOUND_STATUS_RECEIVED = 60;
    public const INBOUND_STATUS_INBOUND_ERROR = 80;
    public const INBOUND_STATUS_INBOUNDED = 100;
    public const INBOUND_STATUS_CANCELLED = 110;
    public const INBOUND_STATUS_TO_CANCELLING = 210;
}
