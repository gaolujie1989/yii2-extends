<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\exporters;

use lujie\charging\models\ShippingTable;
use lujie\charging\searches\ShippingTableSearch;
use lujie\charging\transformers\ShippingTableExportTransformer;
use lujie\data\exchange\ModelFileExporter;

/**
 * Class ShippingTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableFileExporter extends ModelFileExporter
{
    /**
     * @var string
     */
    public $modelClass = ShippingTableSearch::class;

    /**
     * @var array
     */
    public $keyMap = [
        'carrier' => 'Carrier',
        'departure' => 'Departure',
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
    ];

    /**
     * @inheritdoc
     */
    public function initTransformer(): void
    {
        $destinations = ShippingTable::find()->select(['destination'])->distinct()->column();
        $this->keyMap = array_merge($this->keyMap, array_combine($destinations, $destinations));
        parent::initTransformer();
        $this->transformer['transformers'] = array_merge([
            'table' => [
                'class' => ShippingTableExportTransformer::class,
            ]
        ], $this->transformer['transformers']);
    }
}
