<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\fulfillment\constants\FulfillmentConst;

return [
    'fulfillmentAccountType' => [
        'AMAZON' => FulfillmentConst::ACCOUNT_TYPE_AMAZON,
        'PM' => FulfillmentConst::ACCOUNT_TYPE_PM,
        'F4PX' => FulfillmentConst::ACCOUNT_TYPE_F4PX,
        'DESPATCH_CLOUD' => FulfillmentConst::ACCOUNT_TYPE_DESPATCH_CLOUD,
    ],
    'fulfillmentType' => [
        'INBOUND' => FulfillmentConst::FULFILLMENT_TYPE_INBOUND,
        'SHIPPING' => FulfillmentConst::FULFILLMENT_TYPE_SHIPPING,
    ],
    'fulfillmentStatus' => [
        'PENDING' => FulfillmentConst::FULFILLMENT_STATUS_PENDING,
        'PROCESSING' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
        'HOLDING' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
        'PICKING' => FulfillmentConst::FULFILLMENT_STATUS_PICKING,
        'SHIP_ERROR' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
        'SHIPPED' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
        'CANCELLED' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
        'TO_CANCELLING' => FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING,
        'TO_SHIPPING' => FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING,
        'TO_HOLDING' => FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING,
    ],
    'inboundFulfillmentStatus' => [
        'PENDING' => FulfillmentConst::INBOUND_STATUS_PENDING,
        'PROCESSING' => FulfillmentConst::INBOUND_STATUS_PROCESSING,
        'SHIPPED' => FulfillmentConst::INBOUND_STATUS_SHIPPED,
        'ARRIVED' => FulfillmentConst::INBOUND_STATUS_ARRIVED,
        'RECEIVED' => FulfillmentConst::INBOUND_STATUS_RECEIVED,
        'INBOUND_ERROR' => FulfillmentConst::INBOUND_STATUS_INBOUND_ERROR,
        'INBOUNDED' => FulfillmentConst::INBOUND_STATUS_INBOUNDED,
        'CANCELLED' => FulfillmentConst::INBOUND_STATUS_CANCELLED,
        'TO_CANCELLING' => FulfillmentConst::INBOUND_STATUS_TO_CANCELLING,
    ],
    'chargeModelType' => [
        FulfillmentConst::FULFILLMENT_CHARGE_MODEL => 'FULFILLMENT_ORDER',
    ],
];