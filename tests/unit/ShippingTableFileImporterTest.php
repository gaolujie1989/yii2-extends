<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use Yii;
use yii\helpers\VarDumper;

class ShippingTableFileImporterTest extends \Codeception\Test\Unit
{


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
        $startedAt = strtotime('-1 years');
        $endedAt = strtotime('+1 years');
        $importer = new ShippingTableFileImporter();
        $fillOwnerIdTransformer = new FillDefaultValueTransformer(['defaultValues' => [
            'started_time' => date('Y-m-d H:i:s', $startedAt),
            'ended_time' => date('Y-m-d H:i:s', $endedAt),
            'owner_id' => 11,
            'departure' => 'DE',
        ]]);
        /** @var ChainedTransformer $transformer */
        $transformer = $importer->transformer;
        array_unshift($transformer->transformers, $fillOwnerIdTransformer);

        $file = Yii::getAlias('@lujie/charging/templates/ShippingTableTemplate.xlsx');
        $this->assertTrue($importer->import($file), VarDumper::dumpAsString($importer->getErrors()));

        $expected = [
            [
                'carrier' => 'GLS',
                'destination' => 'DE',
                'weight_g_limit' => '3000',
                'length_mm_limit' => '400',
                'width_mm_limit' => '280',
                'height_mm_limit' => '50',
                'height_mm_min_limit' => '0',
                'volume_mm3_limit' => '150000000',
                'l2wh_mm_limit' => '1180',
                'lwh_mm_limit' => '0',
                'lh_mm_limit' => '0',
                'price_cent' => '279',
                'currency' => 'EUR',
            ],
            [
                'carrier' => 'GLS',
                'destination' => 'DE',
                'weight_g_limit' => '31500',
                'length_mm_limit' => '1200',
                'width_mm_limit' => '800',
                'height_mm_limit' => '600',
                'height_mm_min_limit' => '0',
                'volume_mm3_limit' => '150000000',
                'l2wh_mm_limit' => '3000',
                'lwh_mm_limit' => '0',
                'lh_mm_limit' => '0',
                'price_cent' => '419',
                'currency' => 'EUR',
            ],
            [
                'carrier' => 'DPD',
                'destination' => 'DE',
                'weight_g_limit' => '30000',
                'length_mm_limit' => '15',
                'width_mm_limit' => '700',
                'height_mm_limit' => '400',
                'height_mm_min_limit' => '0',
                'volume_mm3_limit' => '200000000',
                'l2wh_mm_limit' => '3000',
                'lwh_mm_limit' => '0',
                'lh_mm_limit' => '0',
                'price_cent' => '349',
                'currency' => 'EUR',
            ],
        ];
        $expected = array_map(static function ($item) use ($startedAt, $endedAt) {
            return array_merge($item, [
                'started_at' => (string)$startedAt,
                'ended_at' => (string)$endedAt,
                'owner_id' => '11',
                'departure' => 'DE',
            ]);
        }, $expected);
        $shippingTables = ShippingTable::find()->asArray()->all();
        $shippingTables = array_map(static function ($item) {
            unset($item['shipping_table_id'], $item['created_at'], $item['created_by'], $item['updated_at'], $item['updated_by']);
            return $item;
        }, $shippingTables);
        $this->assertEquals($expected, $shippingTables, VarDumper::export($shippingTables));
    }
}
