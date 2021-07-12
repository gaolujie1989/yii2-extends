<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\charging\calculators\ShippingTableCalculator;
use lujie\charging\calculators\ChargeTableCalculator;
use lujie\data\loader\ArrayDataLoader;

return [
    'chargeCalculatorLoader' => [
        'class' => ArrayDataLoader::class,
        'data' => [
            'SHIPPING' => [
                'class' => ShippingTableCalculator::class,
            ],
            'OUTBOUND_HANDING' => [
                'class' => ChargeTableCalculator::class,
                'chargeableItemLoader' => [
                    'class' => 'outboundHandingChargeableItemLoader'
                ],
            ],
        ],
    ]
];
