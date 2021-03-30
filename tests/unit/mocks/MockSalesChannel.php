<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit\mocks;

use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\helpers\ArrayHelper;

/**
 * Class MockSalesChannel
 * @package lujie\sales\channel\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockSalesChannel extends BaseSalesChannel
{
    /**
     * @var array[]
     */
    public static $EXTERNAL_ORDERS = [
        [
            'id' => '1',
            'orderNo' => 'ORDER-NO-1',
            'status' => 'wait_payment',
            'orderItems' => [
                [
                    'itemNo' => 'ITEM-NO-1',
                    'qty' => '1',
                ]
            ],
            'createdAt' => '1606533925', //2020-11-28 11:25:25
            'paidAt' => '0',
        ],
        [
            'id' => '2',
            'orderNo' => 'ORDER-NO-2',
            'status' => 'paid',
            'orderItems' => [
                [
                    'itemNo' => 'ITEM-NO-2',
                    'qty' => '2',
                ]
            ],
            'createdAt' => '1606541125', //2020-11-28 13:25:25
            'paidAt' => '1606545325', //2020-11-28 14:35:25
        ],
    ];

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        return array_filter(static::$EXTERNAL_ORDERS, static function ($order) use ($externalOrderKeys) {
            return in_array($order['id'], $externalOrderKeys);
        });
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        return array_filter(static::$EXTERNAL_ORDERS, static function ($order) use ($createdAtFrom, $createdAtTo) {
            return $createdAtFrom <= $order['createdAt'] && $order['createdAt'] <= $createdAtTo;
        });
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        static::$EXTERNAL_ORDERS = ArrayHelper::index(static::$EXTERNAL_ORDERS, 'id');
        static::$EXTERNAL_ORDERS[$channelOrder->external_order_key]['status'] = 'shipped';
        return $this->updateSalesChannelOrder($channelOrder, static::$EXTERNAL_ORDERS[$channelOrder->external_order_key], true);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        static::$EXTERNAL_ORDERS = ArrayHelper::index(static::$EXTERNAL_ORDERS, 'id');
        static::$EXTERNAL_ORDERS[$channelOrder->external_order_key]['status'] = 'cancelled';
        return $this->updateSalesChannelOrder($channelOrder, static::$EXTERNAL_ORDERS[$channelOrder->external_order_key], true);
    }

    protected function updateSalesChannelOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $salesChannelOrder->external_created_at = $externalOrder['createdAt'];
        $salesChannelOrder->external_updated_at = $externalOrder['paidAt'] ?: $externalOrder['createdAt'];
        return parent::updateSalesChannelOrder($salesChannelOrder, $externalOrder);
    }
}
