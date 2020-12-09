<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\fulfillment\constants\FulfillmentConst;

return [
    [
        'fulfillment_daily_stock_movement_id' => 1,
        'fulfillment_account_id' => 1,
        'item_id' => 1,
        'warehouse_id' => 1,
        'external_item_key' => 'ITEM-1',
        'external_warehouse_key' => 'W01',
        'movement_type' => FulfillmentConst::MOVEMENT_TYPE_INBOUND,
        'movement_qty' => 10,
        'movement_count' => 1,
        'movement_date' => '2020-12-01',
    ],
    [
        'fulfillment_daily_stock_movement_id' => 2,
        'fulfillment_account_id' => 1,
        'item_id' => 1,
        'warehouse_id' => 1,
        'external_item_key' => 'ITEM-1',
        'external_warehouse_key' => 'W01',
        'movement_type' => FulfillmentConst::MOVEMENT_TYPE_OUTBOUND,
        'movement_qty' => 5,
        'movement_count' => 1,
        'movement_date' => '2020-12-02',
    ],
    [
        'fulfillment_daily_stock_movement_id' => 3,
        'fulfillment_account_id' => 1,
        'item_id' => 1,
        'warehouse_id' => 1,
        'external_item_key' => 'ITEM-1',
        'external_warehouse_key' => 'W01',
        'movement_type' => FulfillmentConst::MOVEMENT_TYPE_INBOUND,
        'movement_qty' => 10,
        'movement_count' => 1,
        'movement_date' => '2020-12-03',
    ],
];