<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit;


use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\tests\unit\mocks\MockSalesChannel;
use lujie\sales\channel\tests\unit\mocks\MockSalesChannelConnector;
use Yii;

class BaseSalesChannelConnectorTest extends \Codeception\Test\Unit
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

    public function testSalesChannelOrderConnect()
    {
        $channelConnector = new MockSalesChannelConnector([
            'outboundOrderClass' => TestOrder::class,
            'orderStatusMap' => [
                SalesChannelConst::CHANNEL_STATUS_PAID => 10
            ]
        ]);
        $channelConnector->bootstrap(Yii::$app);

        $salesChannel = new MockSalesChannel([
            'account' => new SalesChannelAccount([
                'account_id' => 1
            ]),
        ]);

        $salesChannel->pullNewSalesOrders(0, time());

        $salesChannelOrder = SalesChannelOrder::find()->externalOrderKey(2)->one();
        $this->assertNotNull($salesChannelOrder);
        $this->assertEquals(SalesChannelConst::CHANNEL_STATUS_PAID, $salesChannelOrder->sales_channel_status);
        $this->assertTrue($salesChannelOrder->order_id > 0);

        $testOrder = TestOrder::findOne($salesChannelOrder->order_id);
        $this->assertNotNull($testOrder);
        $this->assertEquals(10, $testOrder->status);
    }

    public function testOutboundOrderConnect()
    {
        $channelConnector = new MockSalesChannelConnector([
            'outboundOrderClass' => TestOrder::class,
            'salesChannelStatusMap' => [
                '100' => SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED
            ]
        ]);
        $channelConnector->bootstrap(Yii::$app);

        $testOrder = new TestOrder();
        $testOrder->save(false);
        $salesChannelOrder = new SalesChannelOrder([
            'sales_channel_account_id' => 1,
            'order_id' => $testOrder->test_order_id,
            'external_order_key' => 'ORDER_1'
        ]);
        $salesChannelOrder->save(false);

        $testOrder->status = 100;
        $testOrder->updated_at = time();
        $testOrder->save(false);
        $salesChannelOrder->refresh();
        $this->assertEquals(SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED, $salesChannelOrder->sales_channel_status);
    }
}