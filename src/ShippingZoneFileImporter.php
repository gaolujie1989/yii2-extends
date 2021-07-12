<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\forms\ShippingZoneForm;
use lujie\data\exchange\ModelFileImporter;

/**
 * Class ShippingZoneFileImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneFileImporter extends ModelFileImporter
{
    /**
     * @var string
     */
    public $modelClass = ShippingZoneForm::class;

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
