<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\charging\ChargeTableFileImporter;
use lujie\charging\forms\ChargeTableForm;
use lujie\charging\models\ChargeTable;
use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use Yii;
use yii\helpers\VarDumper;

class ChargeTableFileImporterTest extends \Codeception\Test\Unit
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

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        ChargeTableForm::$chargeGroups = [
            'ItemStorage' => 'Storage',
            'OutboundHandling' => 'Outbound',
        ];
        ChargeTableForm::$chargeLimitUnits = [
            'ItemStorage' => 'CMB/Day',
            'OutboundHandling' => 'g',
        ];
        $startedAt = strtotime('-1 years');
        $endedAt = strtotime('+1 years');
        $importer = new ChargeTableFileImporter();
        $fillOwnerIdTransformer = new FillDefaultValueTransformer(['defaultValues' => [
            'started_time' => date('Y-m-d H:i:s', $startedAt),
            'ended_time' => date('Y-m-d H:i:s', $endedAt),
            'owner_id' => 11,
        ]]);
        /** @var ChainedTransformer $transformer */
        $transformer = $importer->transformer;
        array_unshift($transformer->transformers, $fillOwnerIdTransformer);

        $file = Yii::getAlias('@lujie/charging/templates/ChargeTableTemplate.xlsx');
        $this->assertTrue($importer->import($file), VarDumper::dumpAsString($importer->getErrors()));

        $expected = [
            [
                'charge_group' => 'Storage',
                'charge_type' => 'ItemStorage',
                'custom_type' => '',
                'min_limit' => '0',
                'max_limit' => '30',
                'limit_unit' => 'CMB/Day',
                'display_limit_unit' => 'CMB/Day',
                'price_cent' => '0',
                'currency' => 'EUR',
                'over_limit_price_cent' => '0',
                'per_limit' => '0',
                'min_over_limit' => '0',
                'max_over_limit' => '0',
                'additional' => '{"discountPercent": 0}',
            ],
            [
                'charge_group' => 'Storage',
                'charge_type' => 'ItemStorage',
                'custom_type' => '',
                'min_limit' => '31',
                'max_limit' => '60',
                'limit_unit' => 'CMB/Day',
                'display_limit_unit' => 'CMB/Day',
                'price_cent' => '35',
                'currency' => 'EUR',
                'over_limit_price_cent' => '0',
                'per_limit' => '0',
                'min_over_limit' => '0',
                'max_over_limit' => '0',
                'additional' => '{"discountPercent": 0}',
            ],
            [
                'charge_group' => 'Storage',
                'charge_type' => 'ItemStorage',
                'custom_type' => '',
                'min_limit' => '61',
                'max_limit' => '999',
                'limit_unit' => 'CMB/Day',
                'display_limit_unit' => 'CMB/Day',
                'price_cent' => '60',
                'currency' => 'EUR',
                'over_limit_price_cent' => '0',
                'per_limit' => '0',
                'min_over_limit' => '0',
                'max_over_limit' => '0',
                'additional' => '{"discountPercent": 0}',
            ],
            [
                'charge_group' => 'Outbound',
                'charge_type' => 'OutboundHandling',
                'custom_type' => '',
                'min_limit' => '0',
                'max_limit' => '1000',
                'limit_unit' => 'g',
                'display_limit_unit' => 'KG',
                'price_cent' => '6',
                'currency' => 'EUR',
                'over_limit_price_cent' => '0',
                'per_limit' => '0',
                'min_over_limit' => '0',
                'max_over_limit' => '0',
                'additional' => '{"discountPercent": 0}',
            ],
            [
                'charge_group' => 'Outbound',
                'charge_type' => 'OutboundHandling',
                'custom_type' => '',
                'min_limit' => '1000',
                'max_limit' => '10000',
                'limit_unit' => 'g',
                'display_limit_unit' => 'KG',
                'price_cent' => '17',
                'currency' => 'EUR',
                'over_limit_price_cent' => '0',
                'per_limit' => '0',
                'min_over_limit' => '0',
                'max_over_limit' => '0',
                'additional' => '{"discountPercent": 0}',
            ],
            [
                'charge_group' => 'Outbound',
                'charge_type' => 'OutboundHandling',
                'custom_type' => '',
                'min_limit' => '1000',
                'max_limit' => '10000',
                'limit_unit' => 'g',
                'display_limit_unit' => 'KG',
                'price_cent' => '17',
                'currency' => 'EUR',
                'over_limit_price_cent' => '15',
                'per_limit' => '1000',
                'min_over_limit' => '10000',
                'max_over_limit' => '20000',
                'additional' => '{"discountPercent": 30}',
            ],
            [
                'charge_group' => 'Outbound',
                'charge_type' => 'OutboundHandling',
                'custom_type' => '',
                'min_limit' => '1000',
                'max_limit' => '10000',
                'limit_unit' => 'g',
                'display_limit_unit' => 'KG',
                'price_cent' => '17',
                'currency' => 'EUR',
                'over_limit_price_cent' => '15',
                'per_limit' => '1000',
                'min_over_limit' => '20000',
                'max_over_limit' => '30000',
                'additional' => '{"discountPercent": 40}',
            ],
        ];
        $expected = array_map(static function ($item) use ($startedAt, $endedAt) {
            return array_merge($item, [
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'owner_id' => 11,
            ]);
        }, $expected);
        $chargeTables = ChargeTable::find()->asArray()->all();
        $chargeTables = array_map(static function ($item) {
            unset($item['charge_table_id'], $item['created_at'], $item['created_by'], $item['updated_at'], $item['updated_by']);
            return $item;
        }, $chargeTables);
        $this->assertEquals($expected, $chargeTables, VarDumper::export($chargeTables));
    }
}
