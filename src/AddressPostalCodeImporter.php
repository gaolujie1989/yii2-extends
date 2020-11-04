<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address;


use lujie\common\address\models\AddressPostalCode;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\data\exchange\transformers\OptionTransformer;
use lujie\extend\constants\StatusConst;

class AddressPostalCodeImporter extends FileImporter
{
    /**
     * @var array
     */
    public $pipeline = [
        'class' => ActiveRecordPipeline::class,
        'modelClass' => AddressPostalCode::class,
        'indexKeys' => ['type', 'country', 'postal_code'],
    ];

    /**
     * @var array
     */
    public $transformer = [
        'class' => ChainedTransformer::class,
        'transformers' => [
            'keyMap' => [
                'class' => KeyMapTransformer::class,
                'keyMap' => [
                    'type' => 'type',
                    'country' => 'country',
                    'postal_code' => 'postal_code',
                    'status' => 'status',
                    'note' => 'note',
                ]
            ],
            'option' => [
                'class' => OptionTransformer::class,
                'options' => [
                    'status' => [
                        'N' => StatusConst::STATUS_INACTIVE,
                        'Y' => StatusConst::STATUS_ACTIVE,
                    ]
                ]
            ],
        ]
    ];
}