<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\helpers;

use lujie\charging\models\ShippingTable;
use lujie\extend\models\Item;
use yii\helpers\ArrayHelper;
use function RingCentral\Psr7\mimetype_from_extension;

class ShippingTableHelper
{
    /**
     * @param Item[] $items
     * @param array $carriers
     * @param array $destinations
     * @param array $departures
     * @return array
     * @inheritdoc
     */
    public static function getShippingPrices(array $items, array $carriers, array $destinations, array $departures = ['DE'], string $activeDate = 'now'): array
    {
        $activeAt = strtotime($activeDate);
        $shippingTables = ShippingTable::find()
            ->activeAt($activeAt)
            ->carrier($carriers)
            ->destination($destinations)
            ->departure($departures)
            ->orderByPrice(SORT_ASC)
            ->all();
        $indexedShippingTables = ArrayHelper::index($shippingTables, null, ['departures', 'destination', 'carrier']);
        $itemShippingPrices = [];
        foreach ($items as $item) {
            foreach ($indexedShippingTables as $departure => $departureShippingTables) {
                foreach ($departureShippingTables as $destination => $destinationShippingTables) {
                    foreach ($destinationShippingTables as $carrier => $carrierShippingTables) {
                        /** @var ShippingTable $shippingTable */
                        foreach ($carrierShippingTables as $shippingTable) {
                            if (static::match($item, $shippingTable)) {
                                $itemShippingPrices[$item->itemId][$departure][$destination][$carrier] = $shippingTable;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $itemShippingPrices;
    }

    /**
     * @param Item $item
     * @param ShippingTable $shippingTable
     * @return bool
     * @inheritdoc
     */
    public static function match(Item $item, ShippingTable $shippingTable): bool
    {
        $target = new ShippingTable();
        $target->weight_g_limit = $item->weightG;
        $target->length_mm_limit = $item->lengthMM;
        $target->width_mm_limit = $item->widthMM;
        $target->height_mm_limit = $item->heightMM;

        $target->length_mm_min_limit = $target->length_mm_limit;
        $target->width_mm_min_limit = $target->width_mm_limit;
        $target->height_mm_min_limit = $target->height_mm_limit;
        $target->l2wh_mm_limit = $target->length_mm_limit + 2 * ($target->width_mm_limit + $target->height_mm_limit);
        $target->lwh_mm_limit = $target->length_mm_limit + $target->width_mm_limit + $target->height_mm_limit;
        $target->lh_mm_limit = $target->length_mm_limit + $target->height_mm_limit;
        $target->volume_mm3_limit = $target->length_mm_limit * $target->width_mm_limit * $target->height_mm_limit;
        $limitAttributes = [
            'weight_g_limit',
            'length_mm_limit', 'width_mm_limit', 'height_mm_limit',
            'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit',
            'volume_mm3_limit',
        ];
        foreach ($limitAttributes as $limitAttribute) {
            if ($shippingTable->{$limitAttribute} && $target->{$limitAttribute} >= $shippingTable->{$limitAttribute}) {
                return false;
            }
        }
        $minLimitAttributes = [
            'length_mm_min_limit', 'width_mm_min_limit', 'height_mm_min_limit',
        ];
        foreach ($minLimitAttributes as $minLimitAttribute) {
            if ($shippingTable->{$minLimitAttribute} && $target->{$minLimitAttribute} <= $shippingTable->{$minLimitAttribute}) {
                return false;
            }
        }
        return true;
    }
}