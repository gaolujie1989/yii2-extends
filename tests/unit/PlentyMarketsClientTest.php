<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\executing\tests\unit\mocks\TestExecutable;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\scheduling\CronTask;
use lujie\scheduling\Scheduler;
use lujie\scheduling\tests\unit\mocks\TestOverlappingTask;
use lujie\scheduling\tests\unit\mocks\TestTask;
use lujie\shopify\ShopifyRestClient;
use Yii;
use yii\helpers\VarDumper;

class PlentyMarketsClientTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testItemCurd(): void
    {
        $pmClient = new PlentyMarketsRestClient();
        $pmClient->apiBaseUrl = 'https://www.cclife-technic.de/rest/';
        $pmClient->username = 'lujie';
        $pmClient->password = 'Gao@1989';
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
}
