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
use Yii;
use yii\authclient\InvalidResponseException;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\ReplaceArrayValue;

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

    public $orderTransformer = PmSalesChannelOrderTransformer::class;

    public $orderDataStorage = null;

    #region External Model Key Field

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

        return parent::updateSalesChannelOrder($salesChannelOrder, $externalOrder, $changeActionStatus);
    }

    /**
     * @param array $externalOrder
     * @return int|null
     * @inheritdoc
     */
    protected function getSalesChannelStatus(array $externalOrder): ?int
    {
        $externalOrderStatus = $externalOrder[$this->externalOrderStatusField];
        $salesChannelStatus = parent::getSalesChannelStatus($externalOrderStatus);
        if ($salesChannelStatus !== null) {
            return $salesChannelStatus;
        }
        if ($externalOrderStatus < 4) {
            return SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT;
        }
        if ($externalOrderStatus < 7) {
            return SalesChannelConst::CHANNEL_STATUS_PAID;
        }
        if ($externalOrderStatus < 8) {
            return SalesChannelConst::CHANNEL_STATUS_SHIPPED;
        }
        if ($externalOrderStatus < 9) {
            return SalesChannelConst::CHANNEL_STATUS_CANCELLED;
        }
        return null;
    }

    #endregion

    #region Order Push

    /**
     * @param string $externalOrderKey
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalOrder(string $externalOrderKey): ?array
    {
        return $this->client->getOrder(['id' => $externalOrderKey]);
    }

    /**
     * @param array $externalOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, SalesChannelOrder $salesChannelOrder): ?array
    {
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            return $this->client->updateOrder($externalOrder);
        }
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            $trackingNumbers = $externalOrder['trackingNumbers'] ?? [];
            if (empty($trackingNumbers)) {
                $message = "Empty trackingNumbers of channel order {$salesChannelOrder->sales_channel_order_id}";
                $salesChannelOrder->addError('order_id', $message);
                return null;
            }

            $externalOrderId = $salesChannelOrder->external_order_key;
            $comments = $externalOrder['notes'] ?? $externalOrder['coments'] ?? [];
            if ($comments) {
                $pmOrder = $this->client->getOrder(['id' => $externalOrderId, 'with' => 'comments']);
                $userComments = ArrayHelper::index($pmOrder['comments'], 'userId');
                $userId = 96;
                if (empty($userComments[$userId])) {
                    $this->client->createComment([
                        'referenceValue' => $externalOrderId,
                        'text' => '<p>' . strtr(implode("\n", $comments), ["\n" => '<br />']) . '</p>',
                        'referenceType' => 'order',
                        'isVisibleForContact' => false,
                        'userId' => $userId,
                    ]);
                }
            }

            $warehouseId = $externalOrder['warehouseId'] ?? '';
            if ($warehouseId) {
                $this->client->updateOrderWarehouse($externalOrderId, $warehouseId);
            }

            $this->client->updateOrderShippingNumbers($externalOrderId, $trackingNumbers);
            return $this->client->getOrder(['id' => $externalOrderId]);
        }
        return null;
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
        return array_merge(...array_values($pmSaveParts));
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
        $message = 'Unknown external item data: ' . Json::encode($externalItem);
        $salesChannelItem->addError('item_id', $message);
        Yii::error($message, __METHOD__);
        return null;
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
     * @throws InvalidResponseException
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

        $pushParts = ['item', 'itemTexts', 'itemImages'];
        $pushPartsCount = count($pushParts);
        $pushedResult = $salesChannelItem->item_pushed_result ?: [];
        $doneIndex = $pushedResult['progress']['done'] ?? 0;

        $savedItem = null;
        foreach ($pushParts as $index => $pushPart) {
            if ($doneIndex > $index) {
                continue;
            }
            $doneIndex = $index;
            $pushedResult['progress'] = ['done' => $index, 'total' => $pushPartsCount, 'message' => $pushPart];
            $salesChannelItem->item_pushed_result = $pushedResult;
            $salesChannelItem->save(false);

            switch ($pushPart) {
                case 'item':
                    if ($externalItem) {
                        if (empty($salesChannelItem->external_item_key)) {
                            $savedItem = $this->client->createItem($externalItem);
                            $this->updateSalesChannelItem($salesChannelItem, $savedItem);
                            $pushedResult = $salesChannelItem->item_pushed_result ?: [];
                        } else {
                            unset($externalItem['variations']);
                            $savedItem = $this->client->updateItem($externalItem);
                        }
                    }
                    break;
                case 'itemTexts':
                    if (isset($relatedParts['itemTexts'])) {
                        $this->savePmItemTexts($relatedParts['itemTexts'], $salesChannelItem);
                    }
                    break;
                case 'itemImages':
                    if (isset($relatedParts['itemImages'])) {
                        $this->savePmItemImages($relatedParts['itemImages'], $salesChannelItem);
                    }
                    break;
                default:
                    break;
            }
        }
        unset($pushedResult['progress']);
        $salesChannelItem->item_pushed_result = $pushedResult;
        $salesChannelItem->save(false);
        return $savedItem ?: $this->client->getItem(['id' => $salesChannelItem->external_item_key]);
    }

    /**
     * @param array $itemTexts
     * @param SalesChannelItem $salesChannelItem
     * @throws InvalidResponseException
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
        $pushedResult = $salesChannelItem->item_pushed_result ?: [];

        $itemId = $salesChannelItem->external_item_key;
        $pmItemImages = $this->client->eachItemImages(['itemId' => $itemId]);
        $pmItemImages = iterator_to_array($pmItemImages, false);
        $pmItemImageIds = ArrayHelper::getColumn($pmItemImages, 'id');

        $externalAdditional = $salesChannelItem->external_item_additional ?: [];
        $itemImageIds = $externalAdditional['itemImageIds'] ?? [];
        $linkedItemImageIds = $this->linkItemImageIds($itemImages, $pmItemImages);
        $linkedItemImageIds = array_map(static function($value) {
            return new ReplaceArrayValue($value);
        }, $linkedItemImageIds);
        $itemImageIds = ArrayHelper::merge($itemImageIds, $linkedItemImageIds);

        $itemImageModelIds = ArrayHelper::map($itemImages, 'modelId', 'modelId');
        //过滤Kiwi已经删除的Image
        $itemImageIds = array_intersect_key($itemImageIds, $itemImageModelIds);
        $toDeleteItemImageIds = array_diff($pmItemImageIds, $itemImageIds);
        if ($toDeleteItemImageIds) {
            $batchRequest = $this->client->createBatchRequest();
            foreach ($toDeleteItemImageIds as $toDeleteItemImageId) {
                $batchRequest->deleteItemImage(['itemId' => $itemId, 'id' => $toDeleteItemImageId]);
            }
            $batchRequest->send();
        }
        //过滤PM已经删除的Image
        $itemImageIds = array_intersect($itemImageIds, $pmItemImageIds);
//        $itemImageAttributeValueMarkets = $this->client->eachItemImageAttributeValueMarkets(['itemId' => $itemId]);
//        $itemImageAttributeValueMarkets = iterator_to_array($itemImageAttributeValueMarkets, false);
//        $itemImageAttributeValueMarkets = ArrayHelper::index($itemImageAttributeValueMarkets, 'valueId', ['imageId']);
        $batchRequest = $this->client->createBatchRequest();
        $itemImageCount = count($itemImages);
        $itemDone = 0;
        foreach ($itemImages as $itemImage) {
            $itemDone++;
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
                $pushedResult['progress']['message'] = "itemImages[{$itemDone}/{$itemImageCount}]";
                $salesChannelItem->item_pushed_result = $pushedResult;
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

    /**
     * @param array $itemImages
     * @param array $pmItemImages
     * @return array
     * @inheritdoc
     */
    protected function linkItemImageIds(array $itemImages, array $pmItemImages): array
    {
        $itemImageIds = [];
        $pmItemImageNameIds = ArrayHelper::map($pmItemImages, 'cleanImageName', 'id');
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
                $itemImageIds[(string)$modelId] = $pmItemImageNameIds[$imageName];
            }
        }
        return $itemImageIds;
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
        // variationSalesPrices, 傻逼PM, 原先可以，后来不行了
        // variationAttributeValues, variationProperties, variationCategories, variationClients,
        // variationBarcodes 傻逼PM, 原先可以，后来不行了
        $relatedParts = [
            'variationSalesPrices' => null,
            'variationBarcodes' => null,
            'variationBundleComponents' => null,
            'variationMarkets' => null,
            'variationSkus' => null,
            'variationImages' => null,
            'itemImageAttributeValues' => null,
        ];
        $relatedParts = array_merge($relatedParts, array_intersect_key($externalItem, $relatedParts));
        $externalItem = array_diff_key($externalItem, $relatedParts);

        $pushParts = [
            'variation',
            'variationSalesPrices',
            'variationBarcodes',
            'variationBundleComponents',
            'variationMarkets',
            'variationSkus',
            'variationImages',
            'itemImageAttributeValues'
        ];
        $pushPartsCount = count($pushParts);
        $pushedResult = $salesChannelItem->item_pushed_result ?: [];
        $doneIndex = $pushedResult['progress']['done'] ?? 0;

        $savedVariation = null;
        $pmVariation = null;
        foreach ($pushParts as $index => $pushPart) {
            if ($doneIndex > $index) {
                continue;
            }
            $doneIndex = $index;
            $pushedResult['progress'] = ['done' => $index, 'total' => $pushPartsCount, 'message' => $pushPart];
            $salesChannelItem->item_pushed_result = $pushedResult;
            $salesChannelItem->save(false);

            if ($pushPart === 'variation') {
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
                } else {
                    unset(
                        $externalItem['variationAttributeValues'],
                        $externalItem['unit'],
                        $externalItem['variationClients'],
                        $externalItem['variationCategories'],
                        $externalItem['mainWarehouseId'],
                    );
                    $savedVariation = $this->client->updateItemVariation($externalItem);
                }
            }

            $variationId = $salesChannelItem->external_item_key;
            $itemId = $externalItem['itemId'];
            if ($pmVariation === null) {
                $pmVariation = $this->client->getItemVariation([
                    'id' => $variationId,
                    'itemId' => $itemId,
                    'with' => 'variationAttributeValues,variationSalesPrices,variationBarcodes,variationBundleComponents,variationMarkets,variationSkus'
                ]);
            }
            switch ($pushPart) {
                case 'variationSalesPrices':
                case 'variationBarcodes':
                case 'variationBundleComponents':
                case 'variationMarkets':
                case 'variationSkus':
                case 'variationImages':
                    if (isset($relatedParts[$pushPart])) {
                        $saveMethod = 'save' . ucfirst($pushPart);
                        $this->client->{$saveMethod}($itemId, $variationId, $relatedParts[$pushPart], $pmVariation[$pushPart] ?? null);
                    }
                    break;
                case 'itemImageAttributeValues':
                    if (isset($relatedParts['itemImageAttributeValues']) && $pmVariation['variationAttributeValues']) {
                        $itemImageAttributeValueMarkets = $this->client->eachItemImageAttributeValueMarkets(['itemId' => $itemId]);
                        $itemImageAttributeValueMarkets = iterator_to_array($itemImageAttributeValueMarkets, false);
                        $itemImageAttributeValueMarkets = ArrayHelper::index($itemImageAttributeValueMarkets, 'valueId', ['imageId']);
                        $relatedItemImageAttributeValues = ArrayHelper::index($relatedParts['itemImageAttributeValues'], 'valueId', ['imageId']);
                        foreach ($relatedItemImageAttributeValues as $imageId => $imageAttributeValues) {
                            $this->client->saveItemImageAttributeValueMarkets($itemId, $imageId, $imageAttributeValues, $itemImageAttributeValueMarkets[$imageId] ?? []);
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        unset($pushedResult['progress']);
        $salesChannelItem->item_pushed_result = $pushedResult;
        $salesChannelItem->save(false);
        return $savedVariation ?: $pmVariation;
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
