<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileExporter;
use lujie\charging\tests\unit\fixtures\ShippingTableFixture;
use lujie\extend\helpers\ExcelHelper;

class ShippingTableFileExporterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

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
        $exporter->source = ShippingTable::find();
        $exporter->export('shippingTable.xlsx');
        $excelData = ExcelHelper::readExcel($exporter->getFilePath());
        $excepted = [
            [
                'Carrier' => 'GLS',
                'Destination' => 'DE',
                'WeightLimit(KG)' => '3',
                'LengthLimit(CM)' => '200',
                'WidthLimit(CM)' => '80',
                'HeightLimit(CM)' => '60',
                'HeightMinLimit(CM)' => '0',
                'Volume(L)' => '150',
                'L+2(W+H)Limit(CM)' => '300',
                '(L+W+H)Limit(CM)' => '0',
                '(L+H)Limit(CM)' => '0',
                'Price' => '4.54',
                'Currency' => 'EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Destination' => 'DE',
                'WeightLimit(KG)' => '15',
                'LengthLimit(CM)' => '200',
                'WidthLimit(CM)' => '80',
                'HeightLimit(CM)' => '60',
                'HeightMinLimit(CM)' => '0',
                'Volume(L)' => '150',
                'L+2(W+H)Limit(CM)' => '300',
                '(L+W+H)Limit(CM)' => '0',
                '(L+H)Limit(CM)' => '0',
                'Price' => '5.74',
                'Currency' => 'EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Destination' => 'DE',
                'WeightLimit(KG)' => '31.5',
                'LengthLimit(CM)' => '200',
                'WidthLimit(CM)' => '80',
                'HeightLimit(CM)' => '60',
                'HeightMinLimit(CM)' => '0',
                'Volume(L)' => '0',
                'L+2(W+H)Limit(CM)' => '300',
                '(L+W+H)Limit(CM)' => '0',
                '(L+H)Limit(CM)' => '0',
                'Price' => '6.69',
                'Currency' => 'EUR',
            ],
            [
                'Carrier' => 'GLS',
                'Destination' => 'DE',
                'WeightLimit(KG)' => '40',
                'LengthLimit(CM)' => '200',
                'WidthLimit(CM)' => '80',
                'HeightLimit(CM)' => '60',
                'HeightMinLimit(CM)' => '0',
                'Volume(L)' => '150',
                'L+2(W+H)Limit(CM)' => '300',
                '(L+W+H)Limit(CM)' => '0',
                '(L+H)Limit(CM)' => '0',
                'Price' => '16.89',
                'Currency' => 'EUR',
            ],
        ];
        $this->assertEquals($excepted, $excelData);
    }
}
