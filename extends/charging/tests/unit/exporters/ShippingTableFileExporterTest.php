<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\charging\exporters\ShippingTableFileExporter;
use lujie\charging\models\ShippingTable;
use lujie\charging\tests\unit\fixtures\ShippingTableFixture;
use lujie\extend\helpers\ExcelHelper;

class ShippingTableFileExporterTest extends \Codeception\Test\Unit
{
    public function _fixtures(): array
    {
        return [
            'shippingTable' => ShippingTableFixture::class
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $exporter = new ShippingTableFileExporter();
        $exporter->source = ShippingTable::find()->asArray();
        $exporter->export('shippingTable.xlsx');
        $excelData = ExcelHelper::readExcel($exporter->getFilePath());
        $excepted = [
            [
                'Carrier' => 'GLS',
                'Departure' => 'DE',
                'Zone' => null,
                'WeightLimit(KG)' => 3,
                'LengthLimit(CM)' => 200,
                'WidthLimit(CM)' => 80,
                'HeightLimit(CM)' => 60,
                'LengthMinLimit(CM)' => 0,
                'WidthMinLimit(CM)' => 0,
                'HeightMinLimit(CM)' => 0,
                'Volume(L)' => 150,
                'L+2(W+H)Limit(CM)' => 300,
                '(L+W+H)Limit(CM)' => 0,
                '(L+H)Limit(CM)' => 0,
                'DE' => '4.54 EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Departure' => 'DE',
                'Zone' => null,
                'WeightLimit(KG)' => 15,
                'LengthLimit(CM)' => 200,
                'WidthLimit(CM)' => 80,
                'HeightLimit(CM)' => 60,
                'LengthMinLimit(CM)' => 0,
                'WidthMinLimit(CM)' => 0,
                'HeightMinLimit(CM)' => 0,
                'Volume(L)' => 150,
                'L+2(W+H)Limit(CM)' => 300,
                '(L+W+H)Limit(CM)' => 0,
                '(L+H)Limit(CM)' => 0,
                'DE' => '5.74 EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Departure' => 'DE',
                'Zone' => null,
                'WeightLimit(KG)' => 31.5,
                'LengthLimit(CM)' => 200,
                'WidthLimit(CM)' => 80,
                'HeightLimit(CM)' => 60,
                'LengthMinLimit(CM)' => 0,
                'WidthMinLimit(CM)' => 0,
                'HeightMinLimit(CM)' => 0,
                'Volume(L)' => 0,
                'L+2(W+H)Limit(CM)' => 300,
                '(L+W+H)Limit(CM)' => 0,
                '(L+H)Limit(CM)' => 0,
                'DE' => '6.69 EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Departure' => 'DE',
                'Zone' => null,
                'WeightLimit(KG)' => 40,
                'LengthLimit(CM)' => 200,
                'WidthLimit(CM)' => 80,
                'HeightLimit(CM)' => 60,
                'LengthMinLimit(CM)' => 0,
                'WidthMinLimit(CM)' => 0,
                'HeightMinLimit(CM)' => 0,
                'Volume(L)' => 150,
                'L+2(W+H)Limit(CM)' => 300,
                '(L+W+H)Limit(CM)' => 0,
                '(L+H)Limit(CM)' => 0,
                'DE' => '16.89 EUR',
            ],
        ];
        $this->assertEquals($excepted, $excelData);
    }
}
