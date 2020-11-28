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
     * @inheritdoc
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $channelOrder->external_order_key]);
        $channelStatus = $this->salesChannelStatusMap[$pmOrder['statusId']];
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_CANCELLED) {
            throw new InvalidArgumentException("Sales order {$channelOrder->external_order_key} is cancelled, can not be shipped");
        }
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
            return $this->updateSalesChannelOrder($channelOrder, $pmOrder);
        }
        $trackingNumbers = $channelOrder->additional['trackingNumbers'] ?? [];
        if (empty($trackingNumbers)) {
            throw new InvalidArgumentException("Empty trackingNumbers of order {$channelOrder->order_id}");
        }
        $orderShippingPackages = $this->client->eachOrderShippingPackages(['orderId' => $channelOrder->external_order_key]);
        $orderShippingPackages = iterator_to_array($orderShippingPackages, false);
        $orderShippingPackages = ArrayHelper::index($orderShippingPackages, 'packageNumber');
        $existTrackingNumbers = array_keys($orderShippingPackages);
        $toCreateTrackingNumbers = array_diff($trackingNumbers, $existTrackingNumbers);
        $toDeleteTrackingNumbers = array_diff($existTrackingNumbers, $trackingNumbers);
        if ($toCreateTrackingNumbers) {
            foreach ($toCreateTrackingNumbers as $trackingNumber) {
                $this->client->createOrderShippingPackage([
                    'orderId' => $channelOrder->external_order_key,
                    'packageNumber' => $trackingNumber,
                ]);
            }
        }
        if ($toDeleteTrackingNumbers) {
            foreach ($toDeleteTrackingNumbers as $trackingNumber) {
                $this->client->deleteOrderShippingPackage($existTrackingNumbers[$trackingNumber]);
            }
        }
        $pmOrder = $this->client->getOrder(['id' => $channelOrder->external_order_key]);
        return $this->updateSalesChannelOrder($channelOrder, $pmOrder);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $channelOrder->external_order_key]);
        $channelStatus = $this->salesChannelStatusMap[$pmOrder['statusId']];
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
            'createdAtFrom' => date('c', $createdAtFrom),
            'createdAtTo' => date('c', $createdAtTo),
            'statusFrom' => '3'
        ];
        $eachOrders = $this->client->eachOrders($condition);
        return iterator_to_array($eachOrders, false);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function updateSalesChannelOrderAdditional(SalesChannelOrder $salesChannelOrder, array $externalOrder): void
    {
        $salesChannelOrder->external_created_at = strtotime($externalOrder['createdAt']);
        $salesChannelOrder->external_updated_at = strtotime($externalOrder['updatedAt']);
        $orderDates = ArrayHelper::map( $externalOrder['dates'], 'typeId', 'date');
        $orderProperties = ArrayHelper::map( $externalOrder['properties'], 'typeId', 'value');
        $orderAddresses = ArrayHelper::index( $externalOrder['addresses'], 'pivot.typeId');
        $orderItems = ArrayHelper::filter($externalOrder['orderItems'], ['!orderItemName', '!properties', '!dates', '!amounts']);
        $salesChannelOrder->external_order_additional = [
            'CreatedOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['CreatedOn']] ?? '',
            'PaidOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['PaidOn']] ?? '',
            'OutgoingItemsBookedOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']] ?? '',
            'external_order_no' => $orderProperties[PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['EXTERNAL_ORDER_ID']] ?? '',
            'orderItems' => $orderItems,
            'shippingAddress' => $orderAddresses[PlentyMarketsConst::ADDRESS_TYPE_IDS['delivery']] ?? [],
        ];
    }

    #endregion
}
