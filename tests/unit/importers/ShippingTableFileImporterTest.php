<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\importers;

use lujie\charging\models\ShippingTable;
use lujie\charging\importers\ShippingTableFileImporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class ShippingTableFileImporterTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $startedAt = strtotime('2022-01-01');
        $endedAt = strtotime('2022-04-01');
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

        $shippingTables = ShippingTable::find()->asArray()->all();
        $shippingTables = array_map(static function ($item) {
            unset($item['shipping_table_id'], $item['created_at'], $item['created_by'], $item['updated_at'], $item['updated_by']);
            return $item;
        }, $shippingTables);

        file_put_contents('/app/test.txt', VarDumper::export($shippingTables));
        $shippingTables = ArrayHelper::index($shippingTables, static function($values) {
            return $values['carrier'] . $values['destination'] . $values['price_cent'];
        });

        $expected = require dirname(__DIR__) . '/fixtures/data/shipping_template_rows.php';
        $expected = ArrayHelper::index($expected, static function($values) {
            return $values['carrier'] . $values['destination'] . $values['price_cent'];
        });
        $this->assertEquals($expected, $shippingTables);
    }
}
