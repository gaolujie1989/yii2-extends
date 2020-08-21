<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\data\exchange\FileExporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

/**
 * Class ChargeTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableFileExporter extends FileExporter
{
    /**
     * @var string[]
     */
    public $transformer = [
        'class' => ChainedTransformer::class,
        'transformers' => [
            'keyMap' => [
                'class' => KeyMapTransformer::class,
                'unsetNotInMapKey' => true,
                'keyMap' => [
                    'charge_type' => 'ChargeType',
                    'display_min_limit' => 'MinLimit',
                    'display_max_limit' => 'MaxLimit',
                    'display_limit_unit' => 'LimitUnit',
                    'price' => 'Price',
                    'currency' => 'Currency',
                    'display_per_limit' => 'OverLimitPerLimit',
                    'over_limit_price' => 'OverLimitPerLimitPrice',
                    'display_min_over_limit' => 'MinOverLimit',
                    'display_max_over_limit' => 'MaxOverLimit',
                    'discountPercent' => 'DiscountPercent(%)',
                ]
            ],
        ]
    ];
}