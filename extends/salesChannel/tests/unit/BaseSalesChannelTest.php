<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit;

use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\tests\unit\mocks\MockSalesChannel;

class BaseSalesChannelTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    protected function getSalesChannel(): BaseSalesChannel
    {
        return new MockSalesChannel([
            'account' => new SalesChannelAccount([
                'account_id' => 1
            ]),
        ]);
    }


    /**
     * @inheritdoc
     */
    public function testPullSalesOrders(): void
    {
        $salesChannelOrders = [
            new SalesChannelOrder([
                'sales_channel_account_id' => 1,
                'external_order_key' => 1,
            ]),
            new SalesChannelOrder([
                'sales_channel_account_id' => 1,
                'external_order_key' => 2,
            ]),
        ];
        $salesChannel = $this->getSalesChannel();
        $salesChannel->pullSalesOrders($salesChannelOrders);

        $query = SalesChannelOrder::find();
        $this->assertEquals(2, $query->count());
        $expected = [
            '1' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
                'external_order_key' => '1',
                'external_order_status' => 'wait_payment',
                'external_created_at' => '1606533925',
                'external_updated_at' => '1606533925',
            ],
            '2' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '2',
                'external_order_status' => 'paid',
                'external_created_at' => '1606541125',
                'external_updated_at' => '1606545325',
            ],
        ];
        $query->select(array_keys(reset($expected)))->indexBy('external_order_key')->asArray();
        $this->assertEquals($expected, $query->all());
    }

    /**
     * @inheritdoc
     */
    public function testPullNewSalesOrders(): void
    {
        $salesChannel = $this->getSalesChannel();
        $salesChannel->pullNewSalesOrders(strtotime('2020-11-28'), strtotime('2020-11-29'));

        $query = SalesChannelOrder::find();
        $this->assertEquals(2, $query->count());
        $expected = [
            '1' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
                'external_order_key' => '1',
                'external_order_status' => 'wait_payment',
                'external_created_at' => '1606533925',
                'external_updated_at' => '1606533925',
            ],
            '2' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '2',
                'external_order_status' => 'paid',
                'external_created_at' => '1606541125',
                'external_updated_at' => '1606545325',
            ],
        ];
        $query->select(array_keys(reset($expected)))->indexBy('external_order_key')->asArray();
        $this->assertEquals($expected, $query->all());
    }

    /**
     * @inheritdoc
     */
    public function testShipSalesOrder(): void
    {
        $salesChannelOrder = new SalesChannelOrder([
            'sales_channel_account_id' => 1,
            'external_order_key' => 2,
        ]);
        $salesChannel = $this->getSalesChannel();
        $this->assertTrue($salesChannel->shipSalesOrder($salesChannelOrder));
        $expected = [
            'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
            'external_order_status' => 'shipped',
        ];
        $this->assertEquals($expected, $salesChannelOrder->getAttributes(array_keys($expected)));
    }

    /**
     * @inheritdoc
     */
    public function testCancelSalesOrder(): void
    {
        $salesChannelOrder = new SalesChannelOrder([
            'sales_channel_account_id' => 1,
            'external_order_key' => 2,
        ]);
        $salesChannel = $this->getSalesChannel();
        $this->assertTrue($salesChannel->cancelSalesOrder($salesChannelOrder));
        $expected = [
            'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
            'external_order_status' => 'cancelled',
        ];
        $this->assertEquals($expected, $salesChannelOrder->getAttributes(array_keys($expected)));
    }
}
