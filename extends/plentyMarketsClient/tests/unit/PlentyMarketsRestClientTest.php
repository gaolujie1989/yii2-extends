<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\plentyMarkets\PlentyMarketsRestClient;
use Yii;
use yii\helpers\ArrayHelper;

class PlentyMarketsRestClientTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return PlentyMarketsRestClient
     * @throws \Exception
     * @inheritdoc
     */
    public function getClient(): PlentyMarketsRestClient
    {
        return new PlentyMarketsRestClient([
            'apiBaseUrl' => ArrayHelper::getValue(Yii::$app->params, 'pm.url'),
            'username' => ArrayHelper::getValue(Yii::$app->params, 'pm.username'),
            'password' => ArrayHelper::getValue(Yii::$app->params, 'pm.password'),
        ]);
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function te1stItemCurd(): void
    {
        $pmClient = $this->getClient();
//        file_put_contents('/tmp/xxx', $pmClient->generateMethodDoc());exit;
        $itemData = [
            'name' => 'TestAbc123',
            'variations' => [
                [
                    'variationCategories' => [
                        ['categoryId' => 616]
                    ],
                    'unit' => [
                        'unitId' => 1,
                        'content' => 1
                    ],
                    'mainWarehouseId' => 108,
                ]
            ],
        ];

        $pmItem = $pmClient->createItem($itemData);
        $this->assertTrue(isset($pmItem['id']));

        $itemData['id'] = $pmItem['id'];
        $pmItem = $pmClient->getItem($itemData);
        $this->assertEquals($itemData['id'], $pmItem['id']);

        $pmClient->deleteItem($pmItem);
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function te1stBatchRequest(): void
    {
        $pmClient = $this->getClient();
        $variationStocks = $pmClient->getWarehouseStocksByVariationIds(['3508', '6970']);
        $variationStocks = ArrayHelper::map($variationStocks, 'warehouseId', 'stockPhysical', 'variationId');
        $this->assertArrayHasKey(3508, $variationStocks);
        $this->assertArrayHasKey(6970, $variationStocks);
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function testUpdateOrderWarehouse(): void
    {
        $client = $this->getClient();
        $client->updateOrderWarehouse(2253809, 121, false);
    }
}
