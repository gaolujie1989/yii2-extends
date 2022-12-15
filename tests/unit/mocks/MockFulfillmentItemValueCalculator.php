<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;

use lujie\fulfillment\FulfillmentItemValueCalculator;

class MockFulfillmentItemValueCalculator extends FulfillmentItemValueCalculator
{
    public static $ITEM_VALUES = [
        1 => [
            '2020-12-01' => 10,
            '2020-12-03' => 20,
        ]
    ];

    public function getItemValue(int $itemId, string $date): int
    {
        return (static::$ITEM_VALUES[$itemId][$date] ?? 10) * 100;
    }
}
