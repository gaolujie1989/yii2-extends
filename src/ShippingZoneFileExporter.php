<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\searches\ShippingZoneSearch;
use lujie\data\exchange\ModelFileExporter;

/**
 * Class ShippingZoneFileImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneFileExporter extends ModelFileExporter
{
    /**
     * @var string
     */
    public $modelClass = ShippingZoneSearch::class;

    /**
     * @var string[]
     */
    public $keyMap = [
        'carrier' => 'Carrier',
        'departure' => 'Departure',
        'destination' => 'Destination',
        'zone' => 'Zone',
        'postal_code_from' => 'PostalCodeFrom',
        'postal_code_to' => 'PostalCodeTo',
    ];
}
