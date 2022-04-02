<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\charging\exporters\ChargeTableFileExporter;
use lujie\charging\models\ChargeTable;
use lujie\charging\tests\unit\fixtures\ChargeTableFixture;
use lujie\extend\helpers\ExcelHelper;
use yii\helpers\VarDumper;

class ChargeTableFileExporterTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'chargeTable' => ChargeTableFixture::class
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $exporter = new ChargeTableFileExporter();
        $exporter->source = ChargeTable::find();
        $this->assertTrue($exporter->export('chargeTable.xlsx'));
        $excelData = ExcelHelper::readExcel($exporter->getFilePath());
        $excepted = [
            [
                'ChargeType' => 'MOCK_CHARGE_T1',
                'MinLimit' => 1,
                'MaxLimit' => 2,
                'LimitUnit' => 'KG',
                'Price' => 1.2,
                'Currency' => 'EUR',
                'OverLimitPerLimitPrice' => 0.0,
                'OverLimitPerLimit' => 0,
                'MinOverLimit' => 0,
                'MaxOverLimit' => 0,
                'DiscountPercent(%)' => 0,
            ],
            [
                'ChargeType' => 'MOCK_CHARGE_T1',
                'MinLimit' => 2,
                'MaxLimit' => 5,
                'LimitUnit' => 'KG',
                'Price' => 2.4,
                'Currency' => 'EUR',
                'OverLimitPerLimitPrice' => 0.3,
                'OverLimitPerLimit' => 1,
                'MinOverLimit' => 5.001,
                'MaxOverLimit' => 10,
                'DiscountPercent(%)' => 20,
            ],
            [
                'ChargeType' => 'MOCK_CHARGE_T1',
                'MinLimit' => 2,
                'MaxLimit' => 5,
                'LimitUnit' => 'KG',
                'Price' => 2.4,
                'Currency' => 'EUR',
                'OverLimitPerLimitPrice' => 0.3,
                'OverLimitPerLimit' => 1,
                'MinOverLimit' => 10.001,
                'MaxOverLimit' => 15,
                'DiscountPercent(%)' => 30,
            ],
            [
                'ChargeType' => 'MOCK_CHARGE_T1',
                'MinLimit' => 2,
                'MaxLimit' => 5,
                'LimitUnit' => 'KG',
                'Price' => 2.4,
                'Currency' => 'EUR',
                'OverLimitPerLimitPrice' => 0.3,
                'OverLimitPerLimit' => 1,
                'MinOverLimit' => 0,
                'MaxOverLimit' => 0,
                'DiscountPercent(%)' => 40,
            ],
        ];
        $this->assertEquals($excepted, $excelData, VarDumper::dumpAsString($excelData));
    }
}
