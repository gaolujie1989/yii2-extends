<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ShippingTable;
use lujie\charging\searches\ShippingTableSearch;
use lujie\data\exchange\FileExporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

/**
 * Class ShippingTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableFileExporter extends FileExporter
{
    /**
     * @var string[]
     */
    public $transformer = [
        'class' => ChainedTransformer::class,
        'transformers' => [
            'prepareRows' => [ShippingTableSearch::class, 'prepareRows'],
            'keyMap' => [
                'class' => KeyMapTransformer::class,
                'unsetNotInMapKey' => true,
                'keyMap' => [
                    'carrier' => 'Carrier',
                    'Departure' => 'departure',
                    'Destination' => 'destination',
                    'Zone' => 'zone',
                    'weight_kg_limit' => 'WeightLimit(KG)',
                    'length_cm_limit' => 'LengthLimit(CM)',
                    'width_cm_limit' => 'WidthLimit(CM)',
                    'height_cm_limit' => 'HeightLimit(CM)',
                    'height_cm_min_limit' => 'HeightMinLimit(CM)',
                    'volume_l_limit' => 'Volume(L)',
                    'l2wh_cm_limit' => 'L+2(W+H)Limit(CM)',
                    'lwh_cm_limit' => '(L+W+H)Limit(CM)',
                    'lh_cm_limit' => '(L+H)Limit(CM)',
                    'price' => 'Price',
                    'currency' => 'Currency',
                ]
            ],
        ]
    ];
}
