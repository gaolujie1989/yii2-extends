<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\pm;

use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\authclient\InvalidResponseException;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\UserException;
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

    #endregion

    #region Order Push ship/cancel

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

        $additional = $channelOrder->additional;
        $notes = $additional['notes'] ?? [];
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
        $trackingNumbers = $additional['trackingNumbers'] ?? [];
        if (empty($trackingNumbers)) {
            throw new InvalidArgumentException("Empty trackingNumbers of order {$channelOrder->order_id}");
        }
        if ($channelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
            $this->client->updateOrderShippingNumbers($orderId, $trackingNumbers);
            return $this->updateSalesChannelOrder($channelOrder, $pmOrder, true);
        }
        $warehouseCode = $additional['warehouseCode'] ?? '';
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
            return $this->updateSalesChannelOrder($channelOrder, $pmOrder, true);
        }
        $pmOrder = $this->client->updateOrder(['id' => $this->orderCancelledStatus]);
        return $this->updateSalesChannelOrder($channelOrder, $pmOrder, true);
    }

    #endregion

    #region Item Push

    /**
     * @var array
     */
    public $pushedPartsMap = [
        SalesChannelConst::ITEM_PUSH_PART_INFO => [
            'variationBarcodes',
            'variationBundleComponents',
            'variationAttributeValues',
            'variationImages',
        ],
        SalesChannelConst::ITEM_PUSH_PART_DESC => ['itemTexts'],
        SalesChannelConst::ITEM_PUSH_PART_IMAGE => ['itemImages'],
        SalesChannelConst::ITEM_PUSH_PART_PRICE => [
            'variationSalesPrices',
            'variationMarkets',
            'variationSkus',
        ],
    ];

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return array
     * @inheritdoc
     */
    public function getPmSaveParts(SalesChannelItem $salesChannelItem): array
    {
        $pushedParts = $salesChannelItem->item_pushed_parts;
        if (empty($salesChannelItem->external_item_key) || empty($pushedParts)) {
            return [SalesChannelConst::ITEM_PUSH_PART_ALL];
        }
        $pmSaveParts = array_intersect_key($this->pushedPartsMap, array_flip($pushedParts));
        return array_merge(...$pmSaveParts);
    }

    /**
     * @param array $externalItem
     * @return array|null
     * @throws UserException
     * @inheritdoc
     */
    protected function getExternalItem(array $externalItem): ?array
    {
        if (isset($externalItem['number'])) {
            $variationNo = $externalItem['number'];
            $isVariation = true;
        } else if (isset($externalItem['variations'][0]['number'])) {
            $variationNo = $externalItem['variations'][0]['number'];
            $isVariation = false;
        } else {
            return null;
        }
        $variations = $this->client->eachVariations(['numberExact' => $variationNo]);
        $variations = iterator_to_array($variations, false);
        if (empty($variations)) {
            return null;
        }
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
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if (isset($externalItem['number'], $externalItem['itemId'])) {
            return $this->savePmVariation($externalItem, $salesChannelItem);
        }
        if (isset($externalItem['attributeId'])) {
            return $this->savePmAttributeValue($externalItem, $salesChannelItem);
        }
        if (isset($externalItem['backendName'])) {
            return $this->savePmAttribute($externalItem, $salesChannelItem);
        }
        if (isset($externalItem['manufacturerId'], $externalItem['producingCountryId'])) {
            return $this->savePmItem($externalItem, $salesChannelItem);
        }
        throw new InvalidArgumentException('Unknown external item data');
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
        if (isset($externalItem['attributeId'])) {
            $externalItemAdditional['attributeId'] = $externalItem['attributeId'];
        }
        $salesChannelItem->external_item_additional = $externalItemAdditional;
        $salesChannelItem->external_item_no = $externalItem['number'] ?? $externalItem['backendName'] ?? '';
        $salesChannelItem->external_created_at = empty($externalItem['createdAt']) ? 0 : strtotime($externalItem['createdAt']);
        $salesChannelItem->external_updated_at = empty($externalItem['updatedAt']) ? 0 : strtotime($externalItem['updatedAt']);
        return parent::updateSalesChannelItem($salesChannelItem, $externalItem);
    }

    #endregion

    #region Save PM Item

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
            'itemTexts' => null,
            'itemImages' => null,
        ];
        $relatedParts = array_intersect_key($externalItem, $relatedParts);
        $externalItem = array_diff_key($externalItem, $relatedParts);
        $additional = $salesChannelItem->additional;
        $additional['step'] = $additional['step'] ?? 'item';
        $savedItem = null;
        if ($additional['step'] === 'item') {
            if ($externalItem) {
                $itemId = $salesChannelItem->external_item_key;
                if (empty($itemId)) {
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
            if ($relatedParts['itemTexts'] !== null) {
                $this->savePmItemTexts($relatedParts['itemTexts'], $salesChannelItem);
                $additional = $salesChannelItem->additional;
            }
            $additional['step'] = 'itemImages';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'itemImages') {
            if ($relatedParts['itemImages'] !== null) {
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
        $mainVariationId = $salesChannelItem->external_item_additional['mainVariationId'];
        $existItemTexts = $this->client->eachItemTexts(['itemId' => $itemId, 'mainVariationId' => $mainVariationId]);
        $pmItemTexts = iterator_to_array($existItemTexts, false);
        $pmItemTexts = ArrayHelper::index($pmItemTexts, 'lang');
        $batchRequest = $this->client->createBatchRequest();
        foreach ($itemTexts as $itemText) {
            $itemText['itemId'] = $itemId;
            $itemText['mainVariationId'] = $mainVariationId;
            if (isset($pmItemTexts[$itemText['lang']])) {
                $batchRequest->updateItemText($itemText);
            } else {
                $batchRequest->createItemText($itemText);
            }
        }
        $batchRequest->send();
    }

    /**
     * @param array $itemImages
     * @param SalesChannelItem $salesChannelItem
     * @throws InvalidResponseException
     * @inheritdoc
     */
    protected function savePmItemImages(array $itemImages, SalesChannelItem $salesChannelItem): void
    {
        $itemId = $salesChannelItem->external_item_key;
        $existItemImages = $this->client->eachItemImages(['itemId' => $itemId]);
        $pmItemImages = iterator_to_array($existItemImages, false);
        $pmItemImages = ArrayHelper::index($pmItemImages, 'id');
        $pmItemImageIds = ArrayHelper::getColumn($pmItemImages, 'id');
        $pmItemImageNameIds = ArrayHelper::map($pmItemImages, 'cleanImageName', 'id');

        $externalAdditional = $salesChannelItem->external_item_additional;
        $itemImageIds = $externalAdditional['itemImageIds'];
        foreach ($itemImages as $itemImage) {
            if (empty($itemImage['uploadImageUrl'])) {
                continue;
            }
            $modelId = $itemImage['modelId'] ?? null;
            if (empty($modelId)) {
                continue;
            }
            $imageName = pathinfo(parse_url($itemImage['uploadImageUrl'], PHP_URL_PATH), PATHINFO_BASENAME);
            $imageName = strtr($imageName, ['_' => '-']);
            if (isset($pmItemImageNameIds[$imageName])) {
                $itemImageIds[$modelId] = $pmItemImageNameIds[$imageName];
            }
        }

        $itemImageIds = array_intersect($itemImageIds, $pmItemImageIds);
        $toDeleteItemImageIds = array_diff($pmItemImageIds, $itemImageIds);
        if ($toDeleteItemImageIds) {
            $batchRequest = $this->client->createBatchRequest();
            foreach ($toDeleteItemImageIds as $toDeleteItemImageId) {
                $batchRequest->deleteItemImage(['itemId' => $itemId, 'id' => $toDeleteItemImageId]);
            }
            $batchRequest->send();
        }
//        $itemImageAttributeValueMarkets = $this->client->eachItemImageAttributeValueMarkets(['itemId' => $itemId]);
//        $itemImageAttributeValueMarkets = iterator_to_array($itemImageAttributeValueMarkets, false);
//        $itemImageAttributeValueMarkets = ArrayHelper::index($itemImageAttributeValueMarkets, 'valueId', ['imageId']);
        $batchRequest = $this->client->createBatchRequest();
        foreach ($itemImages as $itemImage) {
            $attributeValueMarkets = $itemImage['attributeValueMarkets'] ?? null;
            unset($itemImage['attributeValueMarkets']);
            $itemImage['itemId'] = $itemId;
            $modelId = $itemImage['modelId'] ?? null;
            unset($itemImage['modelId']);
            if (empty($modelId)) {
                throw new InvalidArgumentException('Image data must with model id');
            }
            $imageId = $itemImageIds[$modelId] ?? null;
            if ($imageId) {
                $itemImage['id'] = $imageId;
                unset($itemImage['uploadImageData'], $itemImage['uploadImageUrl']);
                $batchRequest->updateItemImage($itemImage);
            } else {
                if (empty($itemImage['uploadImageData']) && isset($itemImage['uploadImageUrl'])) {
                    $itemImage['uploadImageData'] = base64_encode(file_get_contents($itemImage['uploadImageUrl']));
                }
                unset($itemImage['uploadImageUrl']);
                $createdItemImage = $this->client->createItemImage($itemImage);
                $imageId = $createdItemImage['id'];
                $itemImageIds[$modelId] = $imageId;
                $externalAdditional['itemImageIds'] = $itemImageIds;
                $salesChannelItem->external_item_additional = $externalAdditional;
                $salesChannelItem->save(false);
            }
            //放到VariationImage中执行
//            if ($attributeValueMarkets !== null) {
//                $this->client->saveItemImageAttributeValueMarkets($itemId, $imageId, $attributeValueMarkets, $itemImageAttributeValueMarkets[$imageId] ?? []);
//            }
        }
        $externalAdditional['itemImageIds'] = $itemImageIds;
        $salesChannelItem->external_item_additional = $externalAdditional;
        $salesChannelItem->save(false);
        $batchRequest->send();
    }

    #endregion

    #region Save PM Variation

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function savePmVariation(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if (empty($externalItem['itemId'])) {
            throw new InvalidArgumentException('variation data must with item id');
        }
        // 可以自动关联保存:
        // variationBarcodes, variationSalesPrices,
        // variationAttributeValues, variationProperties, variationCategories, variationClients,
        $relatedParts = [
            'variationBundleComponents' => null,
            'variationMarkets' => null,
            'variationSkus' => null,
            'variationImages' => null,
            'itemImageAttributeValues' => null,
        ];
        $relatedParts = array_intersect_key($externalItem, $relatedParts);
        $externalItem = array_diff_key($externalItem, $relatedParts);
        $additional = $salesChannelItem->additional;
        $additional['step'] = $additional['step'] ?? 'variation';
        $savedVariation = null;
        if ($additional['step'] === 'variation') {
            if (empty($salesChannelItem->external_item_key)) {
                try {
                    $savedVariation = $this->client->createItemVariation($externalItem);
                    $this->updateSalesChannelItem($salesChannelItem, $savedVariation);
                } catch (InvalidResponseException $exception) {
                    if ((string)$exception->response->getStatusCode() === '422') {
                        $variations = $this->client->eachItemVariations(['itemId' => $externalItem['itemId']]);
                        $variations = iterator_to_array($variations, false);
                        $variations = ArrayHelper::index($variations, 'number');
                        $variation = $variations[$externalItem['number']] ?? null;
                        if ($variation) {
                            $this->updateSalesChannelItem($salesChannelItem, $variation);
                            $externalItem['id'] = $variation['id'];
                            unset($externalItem['variationAttributeValues']);
                            $savedVariation = $this->client->updateItemVariation($externalItem);
                        } else {
                            throw $exception;
                        }
                    } else {
                        throw $exception;
                    }
                }
                $additional = $salesChannelItem->additional;
            } else {
                unset($externalItem['variationAttributeValues']);
                $savedVariation = $this->client->updateItemVariation($externalItem);
            }
            $additional['step'] = 'variationBundleComponents';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        $variationId = $salesChannelItem->external_item_key;
        $itemId = $externalItem['itemId'];
        $pmVariation = $this->client->getItemVariation([
            'id' => $variationId,
            'itemId' => $itemId,
            'with' => 'variationBundleComponents,variationMarkets,variationSkus'
        ]);
        if ($additional['step'] === 'variationBundleComponents') {
            if ($relatedParts['variationBundleComponents'] !== null) {
                $this->client->saveVariationBundleComponents($itemId, $variationId, $relatedParts['variationBundleComponents'], $pmVariation['variationBundleComponents']);
            }
            $additional['step'] = 'variationMarkets';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'variationMarkets') {
            if ($relatedParts['variationMarkets'] !== null) {
                $this->client->saveVariationMarkets($itemId, $variationId, $relatedParts['variationMarkets'], $pmVariation['variationMarkets']);
            }
            $additional['step'] = 'variationSkus';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'variationSkus') {
            if ($relatedParts['variationSkus'] !== null) {
                $this->client->saveVariationSkus($itemId, $variationId, $relatedParts['variationSkus'], $pmVariation['variationSkus']);
            }
            $additional['step'] = 'variationImages';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'variationImages') {
            if ($relatedParts['variationImages'] !== null) {
                $this->client->saveVariationImages($itemId, $variationId, $relatedParts['variationImages']);
            }
            $additional['step'] = 'itemImageAttributeValues';
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        if ($additional['step'] === 'itemImageAttributeValues') {
            if ($relatedParts['itemImageAttributeValues'] !== null) {
                $itemImageAttributeValueMarkets = $this->client->eachItemImageAttributeValueMarkets(['itemId' => $itemId]);
                $itemImageAttributeValueMarkets = iterator_to_array($itemImageAttributeValueMarkets, false);
                $itemImageAttributeValueMarkets = ArrayHelper::index($itemImageAttributeValueMarkets, 'valueId', ['imageId']);
                $relatedItemImageAttributeValues = ArrayHelper::index($relatedParts['itemImageAttributeValues'], 'valueId', ['imageId']);
                foreach ($relatedItemImageAttributeValues as $imageId => $imageAttributeValues) {
                    $this->client->saveItemImageAttributeValueMarkets($itemId, $imageId, $imageAttributeValues, $itemImageAttributeValueMarkets[$imageId]);
                }
            }
            unset($additional['step']);
            $salesChannelItem->additional = $additional;
            $salesChannelItem->save(false);
        }
        return $savedVariation;
    }

    #endregion

    #region Save PM Attribute / AttributeValue

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function savePmAttribute(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if (empty($salesChannelItem->external_item_key)) {
            try {
                return $this->client->createAttribute($externalItem);
            } catch (InvalidResponseException $exception) {
                if ((string)$exception->response->getStatusCode() === '422') {
                    $attributes = $this->client->eachAttributes();
                    $attributes = iterator_to_array($attributes, false);
                    $attributeIds = ArrayHelper::map($attributes, 'backendName', 'id');
                    $attributeId = $attributeIds[$externalItem['backendName']] ?? null;
                    if ($attributeId) {
                        $externalItem['id'] = $attributeId;
                        return $this->client->updateAttribute($externalItem);
                    }
                }
                throw $exception;
            }
        }
        return $this->client->updateAttribute($externalItem);
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function savePmAttributeValue(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if (empty($externalItem['attributeId'])) {
            throw new InvalidArgumentException('Attribute value data must with attribute id');
        }
        if (empty($salesChannelItem->external_item_key)) {
            try {
                return $this->client->createAttributeValue($externalItem);
            } catch (InvalidResponseException $exception) {
                if ((string)$exception->response->getStatusCode() === '422') {
                    $attributeValues = $this->client->eachAttributeValues(['attributeId' => $externalItem['attributeId']]);
                    $attributeValues = iterator_to_array($attributeValues, false);
                    $attributeValueIds = ArrayHelper::map($attributeValues, 'backendName', 'id');
                    $attributeValueId = $attributeValueIds[$externalItem['backendName']] ?? null;
                    if ($attributeValueId) {
                        $externalItem['id'] = $attributeValueId;
                        return $this->client->updateAttributeValue($externalItem);
                    }
                }
                throw $exception;
            }
        }
        return $this->client->updateAttributeValue($externalItem);
    }

    #endregion

    #region Item Stock Push

    /**
     * @param array $externalItemStocks
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItemStocks(array $externalItemStocks): ?array
    {
        return $this->client->correctStock($externalItemStocks);
    }

    #endregion
}
