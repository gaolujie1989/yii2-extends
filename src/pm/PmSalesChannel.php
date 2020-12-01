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

        $salesChannelOrder->external_order_additional = [
            'CreatedOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['CreatedOn']] ?? '',
            'PaidOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['PaidOn']] ?? '',
            'OutgoingItemsBookedOn' => $orderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']] ?? '',
            'external_order_no' => $orderProperties[PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['EXTERNAL_ORDER_ID']] ?? '',
            'orderItems' => $this->transformPmOrderItems($externalOrder),
            'shippingAddress' => $this->transformPmShippingAddresses($externalOrder),
        ];
    }

    /**
     * @param array $addressData
     * @return array
     * @inheritdoc
     */
    protected function transformPmShippingAddresses(array $externalOrder)
    {
        $orderAddresses = ArrayHelper::index($externalOrder['addresses'], 'pivot.typeId');
        $addressData = $orderAddresses[PlentyMarketsConst::ADDRESS_TYPE_IDS['delivery']] ?? [];
        if (empty($addressData)) {
            return [];
        }
        $addressOptionValues = ArrayHelper::map($addressData['options'], 'typeId', 'value');
        $countryCode = PlentyMarketsConst::COUNTRY_CODES[$addressData['countryId'] ?: 0] ?? '';

        if (empty($addressData['address2']) && preg_match('/\d+/', $addressData['address1'], $matches, PREG_OFFSET_CAPTURE)) {
            $prefixLength = $matches[0][1];
            $streetNoLength = strlen($matches[0][0]);
            if ($countryCode === 'UK' || $countryCode === 'GB') {
                $addressData['address2'] = substr($addressData['address1'], 0, $prefixLength + $streetNoLength);
                $addressData['address1'] = substr($addressData['address1'], $prefixLength + $streetNoLength);
            } else {
                $addressData['address2'] = substr($addressData['address1'], $prefixLength);
                $addressData['address1'] = substr($addressData['address1'], 0, $prefixLength);
            }
        }

        return [
            'address_id' => $addressData['id'],

            'name1' => $addressData['name1'],
            'name2' => $addressData['name2'],
            'name3' => $addressData['name3'],
            'address1' => $addressData['address1'],
            'address2' => $addressData['address2'],
            'address3' => $addressData['address3'],

            'postal_code' => $addressData['postalCode'],
            'town' => $addressData['town'],
            'country' => $countryCode,
            'country_id' => $addressData['countryId'] ?: 0,
            'state_id' => $addressData['stateId'] ?: 0,
            'phone' => $addressOptionValues[PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Telephone']] ?? '',
            'email' => $addressOptionValues[PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Email']] ?? '',

            'pm_created_at' => strtotime($addressData['createdAt']),
            'pm_updated_at' => strtotime($addressData['updatedAt']),
        ];
    }

    /**
     * @param array $pmOrderData
     * @return array
     * @inheritdoc
     */
    protected function transformPmOrderItems(array $pmOrderData): array
    {
        $pmOrderItems = [];
        foreach ($pmOrderData['orderItems'] as $orderItemData) {
            $orderItemReferenceIds = ArrayHelper::map($orderItemData['references'], 'referenceType', 'referenceOrderItemId');
            $orderItemPropertiesValues = ArrayHelper::map($orderItemData['properties'], 'typeId', 'value');
            $pmOrderItems[$orderItemData['id']] = [
                'order_item_id' => $orderItemData['id'],
                'order_id' => $orderItemData['orderId'],
                'variation_id' => $orderItemData['itemVariationId'],
                'item_id' => $orderItemData['variation']['itemId'] ?? 0,

                'variation_no' => $orderItemData['variation']['number'] ?? '',
//                'order_item_name' => $orderItemData['orderItemName'],
                'shipping_profile_id' => $orderItemData['shippingProfileId'],
                'shipping_profile_name' => $this->shippingProfiles[$orderItemData['shippingProfileId']] ?? '',
                'quantity' => $orderItemData['quantity'],

                'bundle_type' => isset($orderItemReferenceIds['bundle']) ? 'bundle_item' : '',
                'bundle_order_item_id' => $orderItemReferenceIds['bundle'] ?? 0,
                'parent_order_item_id' => $orderItemReferenceIds['parent'] ?? 0,

//                'properties' => $orderItemPropertiesValues,

                'pm_created_at' => strtotime($orderItemData['createdAt']),
                'pm_updated_at' => strtotime($orderItemData['updatedAt']),
            ];
        }
        $bundleOrderItemIds = ArrayHelper::getColumn($pmOrderItems, 'bundle_order_item_id');
        foreach ($bundleOrderItemIds as $bundleOrderItemId) {
            if (isset($pmOrderItems[$bundleOrderItemId])) {
                $pmOrderItems[$bundleOrderItemId]['bundle_type'] = 'bundle';
            }
        }
        return $pmOrderItems;
    }

    #endregion
}
