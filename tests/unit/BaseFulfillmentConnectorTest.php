<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\ar\snapshoot\behaviors\tests\unit\fixtures\models\TestItem;
use lujie\fulfillment\BaseFulfillmentConnector;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use yii\db\AfterSaveEvent;

class BaseFulfillmentConnectorTest extends \Codeception\Test\Unit
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

    public function testMe(): void
    {
        $connector = new BaseFulfillmentConnector([
            'itemClass' => TestItem::class,
            'outboundOrderClass' => TestOrder::class,
        ]);

        $account = new FulfillmentAccount([
            'name' => 'TEST',
            'username' => 'TESTER',
            'status' => 10,
        ]);
        $account->save(false);
        $item = new TestItem([
            'item_no' => '',
            'updated_at' => time(),
        ]);
        $item->save(false);
        $event = new AfterSaveEvent([
            'sender' => $item
        ]);
        $connector->afterItemSaved($event);
        $fulfillmentItem = FulfillmentItem::find()
            ->fulfillmentAccountId($account->account_id)
            ->itemId($item->test_item_id)
            ->one();
        $this->assertNotNull($fulfillmentItem);
        $this->assertEquals($item->updated_at, $fulfillmentItem->item_updated_at);
    }
}