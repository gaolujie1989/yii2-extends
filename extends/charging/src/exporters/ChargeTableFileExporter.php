<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\exporters;

use lujie\charging\searches\ChargeTableSearch;
use lujie\data\exchange\ModelFileExporter;

/**
 * Class ChargeTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableFileExporter extends ModelFileExporter
{
    /**
     * @var string
     */
    public $modelClass = ChargeTableSearch::class;

    /**
     * @var string[]
     */
    public $keyMap = [
        'charge_type' => 'ChargeType',
        'custom_type' => 'CustomType',
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
    ];
}
