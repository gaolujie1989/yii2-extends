<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\pm;


use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class PmSalesChannel
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmSalesChannel extends BaseSalesChannel
{
    /**
     * @var PlentyMarketsRestClient
     */
    public $client;

    #region External Model Key Field

    /**
     * @var string
     */
    public $externalOrderKeyField = 'id';

    /**
     * @var string
     */
    public $externalOrderStatusField = 'statusId';

    /**
     * @var array
     */
    public $salesChannelStatusMap = [
        //global
        '3' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
        '4' => SalesChannelConst::CHANNEL_STATUS_PAID,
        '5' => SalesChannelConst::CHANNEL_STATUS_PAID,
        '6' => SalesChannelConst::CHANNEL_STATUS_PAID,
        '7' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
        '7.1' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
        '8' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
        '8.1' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
        //custom
        '5.7' => SalesChannelConst::CHANNEL_STATUS_PAID,
        '15.9' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
    ];

    #endregion

    public $orderCancelledStatus = 8;

    public $orderShippedWarehouseId = 121;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, PlentyMarketsRestClient::class);
    }

    #region Order Action ship/cancel

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $orderId = $channelOrder->external_order_key;
        $pmOrder = $this->client->getOrder(['id' => $orderId, 'with' => 'comments']);

        $notes = $channelOrder->additional['notes'] ?? [];
        //kiwi data userId 96, if kiwi data already commented, skip
        if ($notes && (empty($pmOrder['comments']) || !in_array(96, ArrayHelper::getColumn($pmOrder['comments'], 'userId'), true))) {
            $this->client->createComment([
                'referenceValue' => $channelOrder->external_order_key,
                'text' => '<p>'. strtr($notes[0], ["\n" => '<br />']) .'</p>',
                'referenceType' => 'order',
                'isVisibleForContact' => false,
                'userId' => 96,
            ]);
        }

        $channelStatus = $this->salesChannelStatusMap[$pmOrder['statusId']] ?? null;
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_CANCELLED) {
            throw new InvalidArgumentException("Sales order {$orderId} is cancelled, can not be shipped");
        }
        $trackingNumbers = $channelOrder->additional['trackingNumbers'] ?? [];
        if (empty($trackingNumbers)) {
            throw new InvalidArgumentException("Empty trackingNumbers of order {$channelOrder->order_id}");
        }
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
            $this->client->updateOrderShippingNumbers($orderId, $trackingNumbers);
            return $this->updateSalesChannelOrder($channelOrder, $pmOrder, true);
        }
        if ($this->orderShippedWarehouseId) {
            $this->client->updateOrderWarehouse($orderId, $this->orderShippedWarehouseId);
        }
        $this->client->updateOrderShippingNumbers($orderId, $trackingNumbers);
        $pmOrder = $this->client->getOrder(['id' => $orderId]);
        return $this->updateSalesChannelOrder($channelOrder, $pmOrder, true);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $channelOrder->external_order_key]);
        $channelStatus = $this->salesChannelStatusMap[$pmOrder['statusId']] ?? null;
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
            throw new InvalidArgumentException("Sales order {$channelOrder->external_order_key} is shipped, can not be cancelled");
        }
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_CANCELLED) {
            return $this->updateSalesChannelOrder($channelOrder, $pmOrder);
        }
        $pmOrder = $this->client->updateOrder(['id' => $this->orderCancelledStatus]);
        return $this->updateSalesChannelOrder($channelOrder, $pmOrder);
    }

    #endregion

    #region Order Pull

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        return $this->client->getOrdersByOrderIds($externalOrderKeys);
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        $condition = [
            'updatedAtFrom' => date('c', $createdAtFrom),
            'updatedAtTo' => date('c', $createdAtTo),
            'statusFrom' => '3',
            'statusTo' => '8.9',
        ];
        $eachOrders = $this->client->eachOrders($condition);
        return iterator_to_array($eachOrders, false);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @param bool $changeActionStatus
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    protected function updateSalesChannelOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $salesChannelOrder->external_created_at = strtotime($externalOrder['createdAt']);
        $salesChannelOrder->external_updated_at = strtotime($externalOrder['updatedAt']);
        $salesChannelOrder->external_order_status = $externalOrder['statusId'];
        $orderDates = ArrayHelper::map($externalOrder['dates'], 'typeId', 'date');
        $orderProperties = ArrayHelper::map($externalOrder['properties'], 'typeId', 'value');

        $salesChannelOrder->external_order_additional = [
            'orderedAt' => empty($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['CreatedOn']])
                ? '' : strtotime($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['CreatedOn']]),
            'paidAt' => empty($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['PaidOn']])
                ? '' : strtotime($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['PaidOn']]),
            'shippedAt' => empty($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']])
                ? '' : strtotime($orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']]),
            'externalOrderNo' => $orderProperties[PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['EXTERNAL_ORDER_ID']] ?? '',
        ];

        $this->updateSalesChannelOrderStatus($salesChannelOrder);
        return parent::updateSalesChannelOrder($salesChannelOrder, $externalOrder, $changeActionStatus);
    }

    #endregion

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @inheritdoc
     */
    protected function updateSalesChannelOrderStatus(SalesChannelOrder $salesChannelOrder, bool $changeActionStatus = false): void
    {
        if (empty($this->salesChannelStatusMap[$salesChannelOrder->external_order_status])) {
            $newSalesChannelStatus = null;
            if ($salesChannelOrder->external_order_status < 4) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT;
            } else if ($salesChannelOrder->external_order_status >= 4 && $salesChannelOrder->external_order_status < 7) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_PAID;
            } else if ($salesChannelOrder->external_order_status >= 7 && $salesChannelOrder->external_order_status < 8) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_SHIPPED;
            } else if ($salesChannelOrder->external_order_status >= 8 && $salesChannelOrder->external_order_status < 9) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_CANCELLED;
            }
            if ($newSalesChannelStatus) {
                $statusTransitions = $this->salesChannelStatusActionTransitions[$salesChannelOrder->sales_channel_status] ?? null;
                if ($statusTransitions === null
                    ||($changeActionStatus && in_array($newSalesChannelStatus, $statusTransitions))) {
                    $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
                }
            }
        }
    }
}
