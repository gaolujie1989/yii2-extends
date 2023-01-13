<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\pm;

use lujie\extend\constants\ExecStatusConst;
use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\base\UserException;
use yii\db\BaseActiveRecord;
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

    public $orderShippingWarehouseIds = [
        'F4PX_ES' => 121,
        'F4PX_DE' => 124,
    ];

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
        $orderId = (int)$channelOrder->external_order_key;
        $pmOrder = $this->client->getOrder(['id' => $orderId, 'with' => 'comments']);

        $notes = $channelOrder->additional['notes'] ?? [];
        //kiwi data userId 96, if kiwi data already commented, skip
        if ($notes && (empty($pmOrder['comments']) || !in_array(96, ArrayHelper::getColumn($pmOrder['comments'], 'userId'), true))) {
            $this->client->createComment([
                'referenceValue' => $orderId,
                'text' => '<p>' . strtr($notes[0], ["\n" => '<br />']) . '</p>',
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
        $warehouseCode = $channelOrder->additional['warehouseCode'] ?? '';
        if ($warehouseId = $this->orderShippingWarehouseIds[$warehouseCode] ?? null) {
            $this->client->updateOrderWarehouse($orderId, $warehouseId);
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
            } elseif ($salesChannelOrder->external_order_status >= 4 && $salesChannelOrder->external_order_status < 7) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_PAID;
            } elseif ($salesChannelOrder->external_order_status >= 7 && $salesChannelOrder->external_order_status < 8) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_SHIPPED;
            } elseif ($salesChannelOrder->external_order_status >= 8 && $salesChannelOrder->external_order_status < 9) {
                $newSalesChannelStatus = SalesChannelConst::CHANNEL_STATUS_CANCELLED;
            }
            if ($newSalesChannelStatus) {
                $statusTransitions = $this->salesChannelStatusActionTransitions[$salesChannelOrder->sales_channel_status] ?? null;
                if ($statusTransitions === null
                    || ($changeActionStatus && in_array($newSalesChannelStatus, $statusTransitions, true))) {
                    $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
                }
            }
        }
    }

    #region Item Push

    /**
     * @param BaseActiveRecord $item
     * @param SalesChannelItem $salesChannelItem
     * @return array
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function formatExternalItemData(BaseActiveRecord $item, SalesChannelItem $salesChannelItem): ?array
    {
        throw new NotSupportedException('NotSupported');
    }

    /**
     * @param array $externalItem
     * @return array|null
     * @throws UserException
     * @inheritdoc
     */
    protected function getExternalItem(array $externalItem): ?array
    {
        if (isset($externalItem['variationNo'])) {
            $variationNo = $externalItem['variationNo'];
            $isVariation = true;
        } else if (isset($externalItem['variations'][0]['variationNo'])) {
            $variationNo = $externalItem['variations'][0]['variationNo'];
            $isVariation = false;
        } else {
            return null;
        }
        $variations = $this->client->eachVariations(['numberExact' => $variationNo]);
        $variations = iterator_to_array($variations, false);
        if (count($variations) > 1) {
            throw new UserException("Variations with same variation_no: {$variationNo}");
        }
        if ($isVariation) {
            return $variations[0];
        }
        return $this->client->getItem(['id' => $variations[0]['itemId']]);
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if (isset($externalItem['variationNo'])) {
            return $this->savePmVariation($externalItem, $salesChannelItem);
        }
        return $this->savePmItem($externalItem, $salesChannelItem);
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    protected function savePmItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        // 可以自动关联保存:
        // itemShippingProfiles, itemProperties
        $relatedParts = [
            'itemTexts' => [],
            'itemImages' => [],
        ];
        $relatedParts = array_intersect_key($externalItem, $relatedParts);
        $externalItem = array_diff_key($externalItem, $relatedParts);
        $additional = $salesChannelItem->additional;
        $additional['step'] = $additional['step'] ?? 'item';
        $savedItem = null;
        if ($additional['step'] === 'item') {
            if ($externalItem) {
                if (empty($externalItem['id'])) {
                    $savedItem = $this->client->createItem($externalItem);
                    $this->updateSalesChannelItem($salesChannelItem, $savedItem);
                    $additional = $salesChannelItem->additional;
                } else {
                    unset($externalItem['variations']);
                    $savedItem = $this->client->updateItem($externalItem);
                }
            }
            $additional['step'] = 'itemTexts';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'itemTexts') {
            if (!empty($relatedParts['itemTexts'])) {
                $this->savePmItemTexts($relatedParts['itemTexts'], $salesChannelItem);
                $additional = $salesChannelItem->additional;
            }
            $additional['step'] = 'itemImages';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'itemImages') {
            if (!empty($relatedParts['itemImages'])) {
                $this->savePmItemImages($relatedParts['itemImages'], $salesChannelItem);
                $additional = $salesChannelItem->additional;
            }
            unset($additional['step']);
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        return $savedItem;
    }

    /**
     * @param array $itemTexts
     * @param int $itemId
     * @param int $mainVariationId
     * @inheritdoc
     */
    protected function savePmItemTexts(array $itemTexts, SalesChannelItem $salesChannelItem): void
    {
        $itemId = $salesChannelItem->external_item_key;
        $mainVariationId = $salesChannelItem->additional['mainVariationId'];
        $existItemTexts = $this->client->eachItemTexts(['itemId' => $itemId, 'mainVariationId' => $mainVariationId]);
        $existItemTexts = iterator_to_array($existItemTexts, false);
        $existItemTexts = ArrayHelper::index($existItemTexts, 'lang');
        $batchRequest = $this->client->createBatchRequest();
        foreach ($itemTexts as $itemText) {
            $itemText['itemId'] = $itemId;
            $itemText['mainVariationId'] = $mainVariationId;
            if (isset($existItemTexts[$itemText['lang']])) {
                $batchRequest->updateItemText($itemText);
            } else {
                $batchRequest->createItemText($itemText);
            }
        }
        $batchRequest->send();
    }

    /**
     * @param array $itemImages
     * @param int $itemId
     * @param SalesChannelItem $salesChannelItem
     * @inheritdoc
     */
    protected function savePmItemImages(array $itemImages, SalesChannelItem $salesChannelItem): void
    {
        $itemId = $salesChannelItem->external_item_key;
        $additional = $salesChannelItem->additional;
        $existItemImages = $this->client->eachItemImages(['itemId' => $itemId]);
        $existItemImages = iterator_to_array($existItemImages, false);
        $existItemImageIds = ArrayHelper::getColumn($existItemImages, 'id');
        $imageIds = $additional['imageIds'] ?? [];
        $imageIds = array_diff($imageIds, $existItemImageIds);
        foreach ($itemImages as $itemImage) {
            $itemImage['itemId'] = $itemId;
            if (empty($itemImage['modelId'])) {
                throw new InvalidArgumentException('Image data must with model id');
            }
            $modelId = $itemImage['modelId'];
            unset($itemImage['modelId']);
            $imageId = $imageIds[$modelId] ?? null;
            if ($imageId) {
                $itemImage['id'] = $imageId;
                $this->client->updateItemImage($itemImage);
            } else {
                $createdItemImage = $this->client->createItemImage($itemImage);
                $imageIds[$modelId] = $createdItemImage['id'];
                $additional['imageIds'] = $imageIds;
                $salesChannelItem->additional = $additional;
                $salesChannelItem->save(false);
            }
        }
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    protected function savePmVariation(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        // 可以自动关联保存:
        // variationBarcodes, variationSalesPrices, variationBundleComponents,
        // variationAttributeValues, variationProperties, variationCategories, variationClients,
        // variationMarkets, variationSkus, images,
        if (empty($externalItem['itemId'])) {
            throw new InvalidArgumentException('variation data must with item id');
        }
        if (empty($externalItem['id'])) {
            $variation = $this->client->createItemVariation($externalItem);
        } else {
            $variation = $this->client->updateItemVariation($externalItem);
        }
        return $variation;
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @param array $externalItem
     * @return bool
     * @inheritdoc
     */
    protected function updateSalesChannelItem(SalesChannelItem $salesChannelItem, array $externalItem): bool
    {
        $externalItemAdditional = $salesChannelItem->external_item_additional ?: [];
        if (isset($externalItem['itemId'])) {
            $externalItemAdditional['itemId'] = $externalItem['itemId'];
        }
        if (isset($externalItem['mainVariationId'])) {
            $externalItemAdditional['mainVariationId'] = $externalItem['mainVariationId'];
        }
        $salesChannelItem->external_item_additional = $externalItemAdditional;
        $salesChannelItem->external_item_no = $externalItem['variationNo'] ?? '';
        return parent::updateSalesChannelItem($salesChannelItem, $externalItem);
    }

    #endregion
}
