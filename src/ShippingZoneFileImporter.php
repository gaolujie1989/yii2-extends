<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\forms\ShippingTableForm;
use lujie\charging\forms\ShippingZoneForm;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FilterTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

/**
 * Class ShippingZoneFileImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneFileImporter extends FileImporter
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
                    'Carrier' => 'carrier',
                    'Departure' => 'departure',
                    'Destination' => 'destination',
                    'Zone' => 'zone',
                    'PostalCodeFrom' => 'postal_code_from',
                    'PostalCodeTo' => 'postal_code_to',
                ]
            ],
            'filter' => [
                'class' => FilterTransformer::class,
                'filterKey' => 'carrier'
            ],
        ]
    ];

    /**
     * @var string[]
     */
    public $pipeline = [
        'class' => ActiveRecordPipeline::class,
        'modelClass' => ShippingZoneForm::class,
        'runValidation' => true,
    ];
}
