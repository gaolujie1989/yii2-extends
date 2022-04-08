<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\transformers;

use lujie\charging\transformers\ShippingTableImportTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\extend\helpers\ExcelHelper;
use yii\helpers\VarDumper;

class ShippingTableImportTransformerTest extends \Codeception\Test\Unit
{
    /**
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $data = ExcelHelper::readExcel(dirname(__DIR__) . '/fixtures/data/FBA20220401.xlsx');
        $transformer = new ShippingTableImportTransformer();
        $transform = $transformer->transform($data);
        $this->assertCount(381, $transform);
        $keyMapTransformer = new KeyMapTransformer([
            'keyMap' => array_flip([
                'carrier' => 'Carrier',
                'weight_kg_limit' => 'WeightLimit(KG)',
                'length_cm_limit' => 'LengthLimit(CM)',
                'width_cm_limit' => 'WidthLimit(CM)',
                'height_cm_limit' => 'HeightLimit(CM)',
                'l2wh_cm_limit' => 'L+2(W+H)Limit(CM)',
            ])
        ]);
        $transform = $keyMapTransformer->transform($transform);
        $expected = require dirname(__DIR__) . '/fixtures/data/FBA20220401Rows.php';
        $this->assertEquals($expected, $transform);
    }
}