<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    [
        'shipping_table_id' => 1,
        'carrier' => 'GLS',
        'departure' => 'DE',
        'destination' => 'DE',
        'weight_g_limit' => 3000,
        'length_mm_limit' => 2000,
        'width_mm_limit' => 800,
        'height_mm_limit' => 600,
        'volume_mm3_limit' => 150000000,
        'l2wh_mm_limit' => 3000,
        'price_cent' => 454,
        'currency' => 'EUR',
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1
    ],
    [
        'shipping_table_id' => 2,
        'carrier' => 'GLS',
        'departure' => 'DE',
        'destination' => 'DE',
        'weight_g_limit' => 15000,
        'length_mm_limit' => 2000,
        'width_mm_limit' => 800,
        'height_mm_limit' => 600,
        'volume_mm3_limit' => 150000000,
        'l2wh_mm_limit' => 3000,
        'price_cent' => 574,
        'currency' => 'EUR',
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1
    ],
    [
        'shipping_table_id' => 3,
        'carrier' => 'GLS',
        'departure' => 'DE',
        'destination' => 'DE',
        'weight_g_limit' => 31500,
        'length_mm_limit' => 2000,
        'width_mm_limit' => 800,
        'height_mm_limit' => 600,
        'l2wh_mm_limit' => 3000,
        'volume_mm3_limit' => 0,
        'price_cent' => 669,
        'currency' => 'EUR',
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1
    ],
    [
        'shipping_table_id' => 4,
        'carrier' => 'GLS',
        'departure' => 'DE',
        'destination' => 'DE',
        'weight_g_limit' => 40000,
        'length_mm_limit' => 2000,
        'width_mm_limit' => 800,
        'height_mm_limit' => 600,
        'volume_mm3_limit' => 150000000,
        'l2wh_mm_limit' => 3000,
        'price_cent' => 1689,
        'currency' => 'EUR',
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1
    ],
];
