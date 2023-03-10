<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\transformers;

use lujie\data\exchange\transformers\TransformerInterface;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class ShippingTableTransformer
 * @package lujie\charging\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableExportTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $destinations = array_unique(ArrayHelper::getColumn($data, 'destination'));
        $destinationDefaults = array_fill_keys($destinations, '');
        $data = ArrayHelper::index($data, 'destination', [static function (array $values) {
            $filterValues = ArrayHelper::filter($values, [
                'carrier',
                'departure',
                'zone',

                'weight_g_limit',
                'length_mm_limit',
                'width_mm_limit',
                'height_mm_limit',
                'length_mm_min_limit',
                'width_mm_min_limit',
                'height_mm_min_limit',
                'l2wh_mm_limit',
                'lwh_mm_limit',
                'lh_mm_limit',
                'volume_mm3_limit',

                'weight_kg_limit',
                'length_cm_limit',
                'width_cm_limit',
                'height_cm_limit',
                'length_cm_min_limit',
                'width_cm_min_limit',
                'height_cm_min_limit',
                'volume_l_limit',
                'l2wh_cm_limit',
                'lwh_cm_limit',
                'lh_cm_limit',
            ]);
            return implode('_', $filterValues);
        }]);
        $transformed = [];
        foreach ($data as $destPrices) {
            $price = reset($destPrices);
            unset($price['destination'], $price['price'], $price['price_cent'], $price['currency']);
            $transformed[] = array_merge(
                $price,
                $destinationDefaults,
                ArrayHelper::getColumn($destPrices, static function ($price) {
                    $priceValue = $price['price'] ?? (($price['price_cent'] ?? 0) / 100);
                    return $priceValue . ' ' . ($price['currency'] ?? '');
                })
            );
        }
        return $transformed;
    }
}