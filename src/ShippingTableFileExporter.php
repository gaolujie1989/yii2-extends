<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\forms\ShippingTableForm;
use lujie\charging\models\ShippingTable;
use lujie\charging\transformers\ShippingTableImportTransformer;
use lujie\data\exchange\FileExporter;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FilterTransformer;
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
            'keyMap' => [
                'class' => KeyMapTransformer::class,
                'keyMap' => [
                    'Carrier' => 'carrier',
                    'Destination' => 'destination',
                    'WeightLimit(KG)' => 'weight_kg_limit',
                    'LengthLimit(CM)' => 'length_cm_limit',
                    'WidthLimit(CM)' => 'width_cm_limit',
                    'HeightLimit(CM)' => 'height_cm_limit',
                    'Lx2(H+W)Limit(CM)' => 'l2wh_cm_limit',
                    '(L+H)Limit(CM)' => 'lh_cm_limit',
                    'Price' => 'price',
                    'Currency' => 'currency',
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
        'modelClass' => ShippingTableForm::class,
        'runValidation' => true,
    ];
}