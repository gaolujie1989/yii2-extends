<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit\pm;

use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\pm\PmSalesChannel;
use yii\httpclient\MockTransport;
use yii\httpclient\Response;

/**
 * Class PmSalesChannelTest
 * @package lujie\sales\channel\tests\unit\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmSalesChannelTest extends \Codeception\Test\Unit
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

    protected function getSalesChannel(): PmSalesChannel
    {
        return new PmSalesChannel([
            'account' => new SalesChannelAccount([
                'account_id' => 1
            ]),
            'client' => new PlentyMarketsRestClient(),
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
                'external_order_key' => '1405478',
            ]),
            new SalesChannelOrder([
                'sales_channel_account_id' => 1,
                'external_order_key' => '1405446',
            ]),
        ];

        $salesChannel = $this->getSalesChannel();
        $mockTransport = new MockTransport();
        $salesChannel->client->httpClient->setTransport($mockTransport);
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/pull.json'),
        ]));
        $salesChannel->pullSalesOrders($salesChannelOrders);

        $query = SalesChannelOrder::find();
        $this->assertEquals(2, $query->count());
        $expected = [
            '1405478' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '1405478',
                'external_order_status' => '5',
                'external_created_at' => strtotime('2020-11-27T10:16:47+01:00'),
                'external_updated_at' => strtotime('2020-11-27T11:37:17+01:00'),
            ],
            '1405446' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '1405446',
                'external_order_status' => '5',
                'external_created_at' => strtotime('2020-11-27T10:03:17+01:00'),
                'external_updated_at' => strtotime('2020-11-27T11:00:04+01:00'),
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
        $mockTransport = new MockTransport();
        $salesChannel->client->httpClient->setTransport($mockTransport);
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/pullNew.json'),
        ]));
        $salesChannel->pullNewSalesOrders(strtotime('2020-11-27 16:00:00'), strtotime('2020-11-27 17:00:00'));

        $query = SalesChannelOrder::find();
        $this->assertEquals(2, $query->count());
        $expected = [
            '1405478' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '1405478',
                'external_order_status' => '5',
                'external_created_at' => strtotime('2020-11-27T10:16:47+01:00'),
                'external_updated_at' => strtotime('2020-11-27T11:37:17+01:00'),
            ],
            '1405446' => [
                'sales_channel_status' => SalesChannelConst::CHANNEL_STATUS_PAID,
                'external_order_key' => '1405446',
                'external_order_status' => '5',
                'external_created_at' => strtotime('2020-11-27T10:03:17+01:00'),
                'external_updated_at' => strtotime('2020-11-27T11:00:04+01:00'),
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
    }

    /**
     * @inheritdoc
     */
    public function testCancelSalesOrder(): void
    {
    }
}