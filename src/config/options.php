<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\fulfillment\constants\FulfillmentConst;

return [
    'fulfillmentAccountType' => [
        FulfillmentConst::ACCOUNT_TYPE_AMAZON => 'AMAZON',
        FulfillmentConst::ACCOUNT_TYPE_PM => 'PM',
        FulfillmentConst::ACCOUNT_TYPE_F4PX => 'F4PX',
        FulfillmentConst::ACCOUNT_TYPE_DESPATCH_CLOUD => 'DESPATCH_CLOUD',
    ],
    'fulfillmentType' => [
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => 'INBOUND',
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'SHIPPING',
    ],
    'shippingFulfillmentStatus' => [
        FulfillmentConst::FULFILLMENT_STATUS_PENDING => 'PENDING',
        FulfillmentConst::FULFILLMENT_STATUS_PROCESSING => 'PROCESSING',
        FulfillmentConst::FULFILLMENT_STATUS_HOLDING => 'HOLDING',
        FulfillmentConst::FULFILLMENT_STATUS_PICKING => 'PICKING',
        FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR => 'SHIP_ERROR',
        FulfillmentConst::FULFILLMENT_STATUS_SHIPPED => 'SHIPPED',
        FulfillmentConst::FULFILLMENT_STATUS_CANCELLED => 'CANCELLED',
        FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING => 'TO_CANCELLING',
        FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING => 'TO_SHIPPING',
        FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING => 'TO_HOLDING',
    ],
    'inboundFulfillmentStatus' => [
        FulfillmentConst::INBOUND_STATUS_PENDING => 'PENDING',
        FulfillmentConst::INBOUND_STATUS_PROCESSING => 'PROCESSING',
        FulfillmentConst::INBOUND_STATUS_SHIPPED => 'SHIPPED',
        FulfillmentConst::INBOUND_STATUS_ARRIVED => 'ARRIVED',
        FulfillmentConst::INBOUND_STATUS_RECEIVED => 'RECEIVED',
        FulfillmentConst::INBOUND_STATUS_INBOUND_ERROR => 'INBOUND_ERROR',
        FulfillmentConst::INBOUND_STATUS_INBOUNDED => 'INBOUNDED',
        FulfillmentConst::INBOUND_STATUS_CANCELLED => 'CANCELLED',
        FulfillmentConst::INBOUND_STATUS_TO_CANCELLING => 'TO_CANCELLING',
    ],
    'chargeModelType' => [
        FulfillmentConst::FULFILLMENT_CHARGE_MODEL => 'FULFILLMENT_ORDER',
    ],
];