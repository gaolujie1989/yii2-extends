<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    [
        'charge_table_id' => 1,
        'charge_group' => 'MOCK_CHARGE_G1',
        'charge_type' => 'MOCK_CHARGE_T1',
        'custom_type' => 'MOCK_CHARGE_C1',
        'min_limit' => 1000,
        'max_limit' => 12000,
        'limit_unit' => 'G',
        'display_limit_unit' => 'KG',
        'price_cent' => 120,
        'currency' => 'EUR',
        'over_limit_price_cent' => 0,
        'per_limit' => 0,
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1,
    ],
    [
        'charge_table_id' => 2,
        'charge_group' => 'MOCK_CHARGE_G1',
        'charge_type' => 'MOCK_CHARGE_T1',
        'custom_type' => 'MOCK_CHARGE_C1',
        'min_limit' => 12000,
        'max_limit' => 24000,
        'limit_unit' => 'G',
        'display_limit_unit' => 'KG',
        'price_cent' => 240,
        'currency' => 'EUR',
        'over_limit_price_cent' => 30,
        'per_limit' => 1000,
        'started_at' => strtotime('-7 days'),
        'ended_at' => strtotime('+7 days'),
        'owner_id' => 1,
    ]
];
