<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\forms\ChargeTableForm;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FilterTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

/**
 * Class ChargeTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableImporter extends FileImporter
{
    /**
     * @var string[]
     */
    public $transformer = [
        'class' => ChainedTransformer::class,
        'transformers' => [
            'keyMap' => [
                'class' => KeyMapTransformer::class,
                'keyMap' => [
                    'ChargeType' => 'charge_type',
                    'MinLimit' => 'display_min_limit',
                    'MaxLimit' => 'display_max_limit',
                    'LimitUnit' => 'display_limit_unit',
                    'Price' => 'price',
                    'Currency' => 'currency',
                    'OverLimitPerLimit' => 'display_per_limit',
                    'OverLimitPerLimitPrice' => 'over_limit_price',
                    'MinOverLimit' => 'display_min_over_limit',
                    'MaxOverLimit' => 'display_max_over_limit',
                    'DiscountPercent' => 'discountPercent',
                ]
            ],
            'filter' => [
                'class' => FilterTransformer::class,
                'filterKey' => 'charge_type'
            ],
        ]
    ];

    /**
     * @var string[]
     */
    public $pipeline = [
        'class' => ActiveRecordPipeline::class,
        'modelClass' => ChargeTableForm::class,
        'runValidation' => true,
    ];
}