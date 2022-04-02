<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\transformers;

use lujie\charging\transformers\ShippingTableImportTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
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
        $data = require dirname(__DIR__) . '/fixtures/data/FBA20220401Table.php';
        $transformer = new ShippingTableImportTransformer();
        $transform = $transformer->transform($data);
        $this->assertCount(340, $transform);
        $keyMapTransformer = new KeyMapTransformer([
            'keyMap' => array_flip([
                'carrier' => 'Carrier',
                'weight_kg_limit' => 'Weight (KG)',
                'length_cm_limit' => 'Length (CM)',
                'width_cm_limit' => 'Width (CM)',
                'height_cm_limit' => 'Height (CM)',
                'l2wh_cm_limit' => 'L+2(W+H) (CM)',
                'price' => 'Price',
                'currency' => 'Currency',
            ])
        ]);
        $transform = $keyMapTransformer->transform($transform);
        $expected = require dirname(__DIR__) . '/fixtures/data/FBA20220401Rows.php';
        $this->assertEquals($expected, $transform);
    }
}