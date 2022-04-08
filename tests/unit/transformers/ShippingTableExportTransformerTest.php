<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\transformers;

use lujie\charging\transformers\ShippingTableExportTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use yii\helpers\VarDumper;

class ShippingTableExportTransformerTest extends \Codeception\Test\Unit
{
    public function testMe(): void
    {
        $data = require dirname(__DIR__) . '/fixtures/data/FBA20220401Rows.php';
        $transformer = new ShippingTableExportTransformer();
        $transform = $transformer->transform($data);
        $this->assertCount(44, $transform);
        $keyMapTransformer = new KeyMapTransformer([
            'keyMap' => [
                'carrier' => 'Carrier',
                'weight_kg_limit' => 'WeightLimit(KG)',
                'length_cm_limit' => 'LengthLimit(CM)',
                'width_cm_limit' => 'WidthLimit(CM)',
                'height_cm_limit' => 'HeightLimit(CM)',
                'l2wh_cm_limit' => 'L+2(W+H)Limit(CM)',
            ]
        ]);
        $transform = $keyMapTransformer->transform($transform);
        file_put_contents('/app/test.txt', VarDumper::export($transform));
        $expected = require dirname(__DIR__) . '/fixtures/data/FBA20220401Table.php';
        $this->assertEquals($expected, $transform);
    }
}