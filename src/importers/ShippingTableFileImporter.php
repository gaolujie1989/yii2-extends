<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\importers;

use lujie\charging\forms\ShippingTableForm;
use lujie\charging\transformers\ShippingTableImportTransformer;
use lujie\data\exchange\ModelFileImporter;

/**
 * Class ShippingTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableFileImporter extends ModelFileImporter
{
    /**
     * @var string
     */
    public $modelClass = ShippingTableForm::class;

    /**
     * @var array
     */
    public $keyMap = [
        'carrier' => 'Carrier',
        'departure' => 'Departure',
        'destination' => 'Destination',
        'zone' => 'Zone',
        'weight_kg_limit' => 'WeightLimit(KG)',
        'length_cm_limit' => 'LengthLimit(CM)',
        'width_cm_limit' => 'WidthLimit(CM)',
        'height_cm_limit' => 'HeightLimit(CM)',
        'length_cm_min_limit' => 'LengthMinLimit(CM)',
        'width_cm_min_limit' => 'WidthMinLimit(CM)',
        'height_cm_min_limit' => 'HeightMinLimit(CM)',
        'volume_l_limit' => 'Volume(L)',
        'l2wh_cm_limit' => 'L+2(W+H)Limit(CM)',
        'lwh_cm_limit' => '(L+W+H)Limit(CM)',
        'lh_cm_limit' => '(L+H)Limit(CM)',
        'price' => 'Price',
        'currency' => 'Currency',
    ];

    /**
     * @inheritdoc
     */
    public function initTransformer(): void
    {
        parent::initTransformer();
        $this->transformer = array_merge($this->transformer, [
            'table' => [
                'class' => ShippingTableImportTransformer::class,
            ]
        ]);
    }
}
