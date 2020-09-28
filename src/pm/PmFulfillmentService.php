<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\pm;


use lujie\data\loader\DataLoaderInterface;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\FulfillmentServiceInterface;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\plentyMarkets\PlentyMarketAddressFormatter;
use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmFulfillmentService extends BaseObject implements FulfillmentServiceInterface
{
    /**
     * @var FulfillmentAccount
     */
    public $account;

    /**
     * @var PlentyMarketsRestClient
     */
    public $client;

    /**
     * @var DataLoaderInterface
     */
    public $itemLoader;

    /**
     * @var DataLoaderInterface
     */
    public $orderLoader;

    /**
     * @var array
     */
    public $defaultItemData = [
        'manufacturerId' => 5,
        'producingCountryId' => 31,
    ];

    /**
     * @var array
     */
    public $defaultVariationData = [
        'variationCategories' => [
            ['categoryId' => 616]
        ],
        'unit' => [
            'unitId' => 1,
            'content' => 1
        ],
        'mainWarehouseId' => 108,
    ];

    /**
     * @var array
     */
    public $barcodeIds = [
        'ean' => 1,
        'fnSku' => 5,
        'ownSku' => 8,
    ];

    public $shippingProfiles = [
        6 => 'DHL',
        7 => 'Selbstabholer',
        9 => 'DE POST',
        12 => 'DHL',
        14 => 'DPD',
        16 => 'GLS',
        18 => 'DPD PRIME STAND',
        19 => 'DPD PRIME PRO',
        20 => 'HERMES',
    ];

    public $variationNoPrefix = 'DIB-';
    public $orderNoPrefix = '';
    public $plentyId = 30389;
    public $referrerId = 10;

    public $orderProcessingStatus = 5;
    public $orderCancelledStatus = 8;
    public $orderErrorStatus = 5.5;
    public $orderHoldStatus = 4;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->account === null || !($this->account instanceof FulfillmentAccount)) {
            throw new InvalidConfigException('The property `account` can not be null and must be FulfillmentAccount');
        }
        $this->client = Instance::ensure($this->client, PlentyMarketsRestClient::class);
        $this->itemLoader = Instance::ensure($this->itemLoader, DataLoaderInterface::class);
        $this->orderLoader = Instance::ensure($this->orderLoader, DataLoaderInterface::class);
    }

    #region push cancelling

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool
    {
        if ($fulfillmentItem->fulfillment_account_id !== $this->account->fulfillment_account_id) {
            return false;
        }

        /** @var Item $item */
        $item = $this->itemLoader->get($fulfillmentItem->item_id);
        $pmVariationData = $this->getVariationData($item);

        if (empty($fulfillmentItem->external_item_id) && isset($pmVariationData['variationBarcodes'])) {
            foreach ($pmVariationData['variationBarcodes'] as ['barcodeId' => $barcodeId, 'code' => $barcode]) {
                $eachVariation = $this->client->eachVariation(['barcode' => $barcode]);
                if ($pmVariation = $eachVariation->current()) {
                    $fulfillmentItem->external_item_id = $pmVariation['id'];
                    $fulfillmentItem->external_item_parent_id = $pmVariation['itemId'];
                    $fulfillmentItem->external_item_no = $pmVariation['number'];
                    $fulfillmentItem->external_created_at = strtotime($pmVariation['createdAt']);
                    $fulfillmentItem->external_updated_at = $pmVariation['updatedAt'];
                    $fulfillmentItem->mustSave(false);
                    break;
                }
            }
        }

        if ($fulfillmentItem->external_item_id) {
            $pmVariationData['id'] = $fulfillmentItem->external_item_id;
            $pmVariationData['itemId'] = $fulfillmentItem->external_item_parent_id;

            $pmItemData = array_merge($this->defaultItemData, ['id' => $pmVariationData['itemId']]);
            $this->client->updateItem($pmItemData);
            $pmVariation = $this->client->updateItemVariation($pmVariationData);
            $fulfillmentItem->external_item_no = $pmVariation['number'];
            $fulfillmentItem->external_created_at = strtotime($pmVariation['createdAt']);
            $fulfillmentItem->external_updated_at = strtotime($pmVariation['updatedAt']);
        } else {
            $pmItemData = array_merge($this->defaultItemData, ['variations' => [$pmVariationData]]);
            $pmItem = $this->client->createItem($pmItemData);
            $pmVariation = $pmItem['variations'][0];

            $fulfillmentItem->external_item_id = $pmVariation['id'];
            $fulfillmentItem->external_item_no = $pmVariation['number'];
            $fulfillmentItem->external_item_parent_id = $pmVariation['itemId'];
            $fulfillmentItem->external_created_at = strtotime($pmVariation['createdAt']);
            $fulfillmentItem->external_updated_at = strtotime($pmVariation['updatedAt']);
        }
        return true;
    }

    /**
     * @param Item $item
     * @return array
     * @inheritdoc
     */
    protected function getVariationData(Item $item): array
    {
        $pmVariationData = array_merge($this->defaultVariationData, [
            'number' => $this->variationNoPrefix . $item->itemNo,
            'name' => $item->itemName ?? $item->itemNo,
            'weightG' => $item->weightG,
            'weightNetG' => $item->weightNetG,
            'lengthMM' => $item->lengthMM,
            'widthMM' => $item->widthMM,
            'heightMM' => $item->heightMM,
        ]);
        $variationBarcodes = [];
        foreach ($this->barcodeIds as $barcodeKey => $barcodeId) {
            $barcode = $item->getBarcode($barcodeKey);
            if ($barcode) {
                $variationBarcodes[] = ['barcodeId' => $barcodeId, 'code' => $barcode];
            }
        }
        if ($variationBarcodes) {
            $pmVariationData['variationBarcodes'] = $variationBarcodes;
        }
        return $pmVariationData;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if ($fulfillmentOrder->fulfillment_account_id !== $this->account->fulfillment_account_id) {
            return false;
        }
//        if ($fulfillmentOrder->external_order_id) {
//            return true;
//        }

        /** @var Order $order */
        $order = $this->orderLoader->get($fulfillmentOrder->order_id);
        if (empty($order->orderItems)) {
            return false;
        }

        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        if (empty($fulfillmentOrder->external_order_id) && $order->orderNo) {
            $externalOrderId = $this->orderNoPrefix . $order->orderNo;
            $eachOrder = $this->client->eachOrder([
                'externalOrderId' => $externalOrderId,
                'plentyId' => $this->plentyId,
                'with' => 'addresses'
            ]);
            if ($pmOrder = $eachOrder->current()) {
                $pmOrderProperties = ArrayHelper::map($pmOrder['properties'], 'typeId', 'value');
                $pmOrderRelationIds = ArrayHelper::map($pmOrder['relations'], 'relation', 'relation');
                $pmAddressIds = ArrayHelper::map($pmOrder['addresses'], 'pivot.typeId', 'id');
                $fulfillmentOrder->external_order_id = $pmOrder['id'];
                $fulfillmentOrder->external_order_no = $pmOrderProperties[7] ?? '';
                $fulfillmentOrder->external_order_status = $pmOrder['statusId'];
                $externalOrderAdditional['plentyId'] = $pmOrder['plentyId'];
                $externalOrderAdditional['customerId'] = $pmOrderRelationIds['receiver'] ?? 0;
                $externalOrderAdditional['addressId'] = $pmAddressIds[2] ?? $pmAddressIds[1] ?? 0;
                $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
                $fulfillmentOrder->external_created_at = strtotime($pmOrder['createdAt']);
                $fulfillmentOrder->external_updated_at = strtotime($pmOrder['updatedAt']);
                $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PROCESSING;
                if ($pmOrder['statusId'] === $this->orderCancelledStatus) {  //if is cancelled order, set to ship
                    $pmOrder = $this->client->updateOrder(['id' => $fulfillmentOrder->external_order_id, 'statusId' => $this->orderProcessingStatus]);
                    $fulfillmentOrder->external_order_status = $pmOrder['statusId'];
                }
                $fulfillmentOrder->mustSave(false);
            }
        }

        if (empty($externalOrderAdditional) || empty($externalOrderAdditional['customerId'])) {
            $pmCustomer = $this->pushPmCustomer($order->address);
            $externalOrderAdditional = array_merge($externalOrderAdditional ?? [], [
                'customerId' => $pmCustomer['id']
            ]);
            $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
            $fulfillmentOrder->mustSave(false);
        }
        if (empty($externalOrderAdditional) || empty($externalOrderAdditional['addressId'])) {
            $pmAddress = $this->pushPmCustomerAddress((int)$externalOrderAdditional['customerId'], $order->address);
        } else {
            $pmAddress = $this->pushPmCustomerAddress((int)$externalOrderAdditional['customerId'], $order->address, (int)$externalOrderAdditional['addressId']);
        }
        $externalOrderAdditional = array_merge($externalOrderAdditional ?? [], [
            'addressId' => $pmAddress['id']
        ]);
        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
        $fulfillmentOrder->mustSave(false);

        if (empty($fulfillmentOrder->external_order_id)) {
            $orderData = $this->getOrderData($order, $externalOrderAdditional);
            $pmOrder = $this->client->createOrder($orderData);
            $pmOrderProperties = ArrayHelper::map($pmOrder['properties'], 'typeId', 'value');
            $fulfillmentOrder->external_order_id = $pmOrder['id'];
            $fulfillmentOrder->external_order_no = $pmOrderProperties[7] ?? '';
            $fulfillmentOrder->external_order_status = $pmOrder['statusId'];
            $externalOrderAdditional['plentyId'] = $pmOrder['plentyId'];
            $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
            $fulfillmentOrder->external_created_at = strtotime($pmOrder['createdAt']);
            $fulfillmentOrder->external_updated_at = strtotime($pmOrder['updatedAt']);
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PROCESSING;
            return $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder);
        } else {
            $this->client->updateOrder([
                'id' => $fulfillmentOrder->external_order_id,
                'addressRelations' => [
                    [
                        'typeId' => 1,
                        'addressId' => $externalOrderAdditional['addressId'],
                    ],
                    [
                        'typeId' => 2,
                        'addressId' => $externalOrderAdditional['addressId'],
                    ],
                ],
            ]);
        }
        return true;
    }

    /**
     * @param Order $order
     * @param array $externalOrderAdditional
     * @return array
     * @inheritdoc
     */
    protected function getOrderData(Order $order, array $externalOrderAdditional): array
    {
        $orderItems = [];
        foreach ($order->orderItems as $orderItem) {
            $orderItems[] = $this->getOrderItemData($orderItem);
        }
        return [
            'typeId' => 1,
            'plentyId' => $this->plentyId,
            'statusId' => $this->orderProcessingStatus,
            'orderItems' => $orderItems,
            'properties' => [
                [
                    'typeId' => 3,
                    'value' => '14',
                ],
                [
                    'typeId' => 4,
                    'value' => 'fullyPaid',
                ],
                [
                    'typeId' => 7,
                    'value' => $this->orderNoPrefix . $order->orderNo,
                ],
            ],
            'addressRelations' => [
                [
                    'typeId' => 1,
                    'addressId' => $externalOrderAdditional['addressId'],
                ],
                [
                    'typeId' => 2,
                    'addressId' => $externalOrderAdditional['addressId'],
                ],
            ],
            'relations' => [
                [
                    'referenceType' => 'contact',
                    'referenceId' => $externalOrderAdditional['customerId'],
                    'relation' => 'receiver',
                ],
            ],
        ];
    }

    protected function getOrderItemData(OrderItem $orderItem)
    {
        $fulfillmentItem = FulfillmentItem::find()
            ->accountId($this->account->fulfillment_account_id)
            ->itemId($orderItem->itemId)
            ->one();
        return [
            'typeId' => 1,
            'referrerId' => $this->referrerId,
            'itemVariationId' => $fulfillmentItem->external_item_id,
            'quantity' => $orderItem->orderedQty,
            'countryVatId' => 1,
            'vatField' => 0,
            'vatRate' => 19,
            'orderItemName' => $orderItem->orderItemName ?: $orderItem->itemNo,
            'properties' => [
                [
                    'typeId' => 1,
                    'value' => '108',
                ],
            ],
            'amounts' => [
                [
                    'isSystemCurrency' => true,
                    'currency' => 'EUR',
                    'exchangeRate' => 1,
                    'priceOriginalGross' => 0,
                    'surcharge' => 0,
                    'discount' => 0,
                    'isPercentage' => true,
                ],
            ],
        ];
    }

    /**
     * @param Address $address
     * @return array
     * @inheritdoc
     */
    protected function pushPmCustomer(Address $address): array
    {
        if ($address->phone) {
            $customers = $this->client->eachCustomer(['privatePhone' => $address->phone]);
            if ($customer = $customers->current()) {
                return $customer;
            }
        }
        if ($address->email) {
            $customers = $this->client->eachCustomer(['email' => $address->email]);
            if ($customer = $customers->current()) {
                return $customer;
            }
        }

        $contactData = [
            'referrerId' => $this->referrerId,
            'plentyId' => $this->plentyId,
            'typeId' => 1,
            'firstName' => $address->firstName,
            'lastName' => $address->lastName,
            'email' => $address->email,
        ];
        $contactOptions = [];
        if ($address->phone) {
            $contactOptions[] = [
                'typeId' => 1,
                'subTypeId' => 4,
                'value' => (string)$address->phone,
                'priority' => 0,
            ];
        }
        if ($address->email) {
            $contactOptions[] = [
                'typeId' => 2,
                'subTypeId' => 4,
                'value' => (string)$address->email,
                'priority' => 0,
            ];
        }
        if ($contactOptions) {
            $contactData['options'] = $contactOptions;
        }
        return $this->client->createCustomer($contactData);
    }

    /**
     * @param int $concatId
     * @param Address $address
     * @param int|null $addressId
     * @return array
     */
    protected function pushPmCustomerAddress(int $concatId, Address $address, ?int $addressId = null): array
    {
        $countryCodeToId = array_flip(array_unique(PlentyMarketsConst::COUNTRY_CODES));
        //fix GB to UK
        $countryCodeToId['GB'] = $countryCodeToId['UK'];
        if (empty($countryCodeToId[$address->country])) {
            throw new InvalidArgumentException('Invalid country: ' . $address->country);
        }

        $addressData = [
            'contactId' => $concatId,
            'name1' => $address->companyName,
            'name2' => $address->firstName,
            'name3' => $address->lastName,

            'address1' => $address->street,
            'address2' => $address->houseNo,
            'address3' => $address->additional,
            'postalCode' => $address->postalCode,
            'town' => $address->city,
            'countryId' => $countryCodeToId[$address['country']],
        ];

        $addressData = PlentyMarketAddressFormatter::format($addressData);

        $addressOptions = [];
        if ($address->phone) {
            $addressOptions[] = [
                'typeId' => 4,
                'value' => (string)$address->phone,
            ];
        }
        if ($address->email) {
            $addressOptions[] = [
                'typeId' => 5,
                'value' => (string)$address->email,
            ];
        }
        if ($addressOptions) {
            $addressData['options'] = $addressOptions;
        }
        if ($addressId) {
            $addressData['id'] = $addressId;
            return $this->client->updateAddress($addressData);
        }
        return $this->client->createCustomerAddress($addressData);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $fulfillmentOrder->external_order_id]);
        if ($this->isOrderAllowHolding($pmOrder)) {
            $pmOrder = $this->client->updateOrder(['id' => $fulfillmentOrder->external_order_id, 'statusId' => $this->orderHoldStatus]);
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder);
        return $pmOrder['statusId'] === $this->orderHoldStatus;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $fulfillmentOrder->external_order_id]);
        if ($this->isOrderAllowShipping($pmOrder)) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
            $pmOrder = $this->client->updateOrder(['id' => $fulfillmentOrder->external_order_id, 'statusId' => $this->orderProcessingStatus]);
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder);
        return $pmOrder['statusId'] === $this->orderProcessingStatus;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $pmOrder = $this->client->getOrder(['id' => $fulfillmentOrder->external_order_id]);
        if ($this->isOrderAllowCancelled($pmOrder)) {
            $pmOrder = $this->client->updateOrder(['id' => $fulfillmentOrder->external_order_id, 'statusId' => $this->orderCancelledStatus]);
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder);
        return $pmOrder['statusId'] === $this->orderCancelledStatus
            || ($pmOrder['statusId'] >= 8 && $pmOrder['statusId'] < 9);
    }

    /**
     * @param array $pmOrder
     * @return bool
     * @inheritdoc
     */
    protected function isOrderAllowHolding(array $pmOrder): bool
    {
        $pmStatusId = $pmOrder['statusId'];
        return $pmStatusId >= 5 && $pmStatusId < 6;
    }

    /**
     * @param array $pmOrder
     * @return bool
     * @inheritdoc
     */
    protected function isOrderAllowShipping(array $pmOrder): bool
    {
        $pmStatusId = $pmOrder['statusId'];
        return ($pmStatusId >= 4 && $pmStatusId < 5) || ($pmStatusId >= 6.5 && $pmStatusId < 7);
    }

    /**
     * @param array $pmOrder
     * @return bool
     * @inheritdoc
     */
    protected function isOrderAllowCancelled(array $pmOrder): bool
    {
        $pmStatusId = $pmOrder['statusId'];
        return $pmStatusId >= 4 && $pmStatusId < 6;
    }

    #endregion

    #region pull and update

    /**
     * @param array $condition
     * @throws Exception
     * @inheritdoc
     */
    public function pullWarehouses(array $condition = []): void
    {
        $warehouses = $this->client->eachWarehouse($condition);
        foreach ($warehouses as $pmWarehouse) {
            $fulfillmentWarehouse = FulfillmentWarehouse::find()
                ->accountId($this->account->fulfillment_account_id)
                ->externalWarehouseId($pmWarehouse['id'])
                ->one();
            if ($fulfillmentWarehouse === null) {
                $fulfillmentWarehouse = new FulfillmentWarehouse();
                $fulfillmentWarehouse->fulfillment_account_id = $this->account->fulfillment_account_id;
                $fulfillmentWarehouse->external_warehouse_id = $pmWarehouse['id'];
            }
            $fulfillmentWarehouse->external_warehouse_name = $pmWarehouse['id'];
            $fulfillmentWarehouse->mustSave(false);
        }
    }

    /**
     * @param FulfillmentOrder[] $fulfillmentOrders
     * @throws Exception
     * @inheritdoc
     */
    public function pullFulfillmentOrders(array $fulfillmentOrders): void
    {
        $fulfillmentOrders = ArrayHelper::index($fulfillmentOrders, 'external_order_id');
        $externalOrderIds = array_keys($fulfillmentOrders);
        $pmOrders = $this->client->getOrdersByOrderIds($externalOrderIds);
        $pmOrderPackageNumbers = $this->client->getOrderPackageNumbersByOrderIds($externalOrderIds);
        foreach ($pmOrders as $pmOrder) {
            $pmOrderId = $pmOrder['id'];
            $this->updateFulfillmentOrder($fulfillmentOrders[$pmOrderId], $pmOrder, $pmOrderPackageNumbers[$pmOrderId] ?? []);
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $pmOrder
     * @param array|null $packageNumbers
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $pmOrder, ?array $packageNumbers = null): bool
    {
        $pmStatus = $pmOrder['statusId'];
        $pmOrderProperties = ArrayHelper::map($pmOrder['properties'], 'typeId', 'value');
        $pmOrderDates = ArrayHelper::map($pmOrder['dates'], 'typeId', 'value');
        $fulfillmentOrder->external_order_status = $pmStatus;
        $fulfillmentOrder->external_updated_at = strtotime($pmOrder['updatedAt']);
        if ($pmStatus === $this->orderCancelledStatus) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_CANCELLED;
        } else if ($pmStatus === $this->orderErrorStatus) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_SHIP_PENDING;
        } else if ($pmStatus >= 4 && $pmStatus < 5) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_HOLDING;
        } else if ($pmStatus >= 5 && $pmStatus < 6) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PROCESSING;
        } else if ($pmStatus >= 6 && $pmStatus < 6.5) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PICKING;
        } else if ($pmStatus >= 6.5 && $pmStatus < 7) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_SHIP_PENDING;
        } else if ($pmStatus >= 7 && $pmStatus < 8) {
            if (empty($packageNumbers)) {
                $packageNumbers = $this->client->getOrderPackageNumbers(['orderId' => $pmOrder['id']]);
            }
            if ($packageNumbers) {
                $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_SHIPPED;
            }
            $additional = $fulfillmentOrder->external_order_additional;
            $additional['packageNumbers'] = $packageNumbers ?: [];
            $additional['shippingProfileId'] = $pmOrderProperties[2] ?? 0;
            $additional['carrier'] = $this->shippingProfiles[$additional['shippingProfileId']] ?? '';
            $additional['shippingAt'] = isset($pmOrderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']])
                ? strtotime($pmOrderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']]) : 0;
            $fulfillmentOrder->external_order_additional = $additional;
        } else if (($pmStatus >= 8 && $pmStatus < 9)) {
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_CANCELLED;
        }
        $fulfillmentOrder->order_pulled_at = time();
        return $fulfillmentOrder->mustSave(false);
    }

    /**
     * @param FulfillmentItem[] $fulfillmentItems
     * @throws Exception
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        $warehouseIds = FulfillmentWarehouse::find()
            ->accountId($this->account->fulfillment_account_id)
            ->getWarehouseIdsIndexByExternalWarehouseId();
        $externalWarehouseIds = array_keys($warehouseIds);

        $now = time();
        $externalItemId2ItemIds = ArrayHelper::map($fulfillmentItems, 'external_item_id', 'item_id');
        $externalItemIds = array_keys($externalItemId2ItemIds);
        $fulfillmentWarehouseStocks = FulfillmentWarehouseStock::find()
            ->accountId($this->account->fulfillment_account_id)
            ->externalWarehouseId($externalWarehouseIds)
            ->externalItemId($externalItemIds)
            ->indexBy(static function ($model) {
                /** @var FulfillmentWarehouseStock $model */
                return $model->external_warehouse_id . '-' . $model->external_item_id;
            })
            ->all();

        $pmWarehouseStocks = $this->client->getWarehouseStocksByVariationIds($externalItemIds);
        foreach ($pmWarehouseStocks as $pmStock) {
            if (empty($warehouseIds[$pmStock['warehouseId']])) {
                continue;
            }
            $key = $pmStock['warehouseId'] . '-' . $pmStock['variationId'];
            $fulfillmentWarehouseStock = $fulfillmentWarehouseStocks[$key] ?? new FulfillmentWarehouseStock();
            $fulfillmentWarehouseStock->fulfillment_account_id = $this->account->fulfillment_account_id;
            $fulfillmentWarehouseStock->external_warehouse_id = $pmStock['warehouseId'];
            $fulfillmentWarehouseStock->external_item_id = $pmStock['variationId'];
            $fulfillmentWarehouseStock->warehouse_id = $warehouseIds[$pmStock['warehouseId']];
            $fulfillmentWarehouseStock->item_id = $externalItemId2ItemIds[$pmStock['variationId']];
            $fulfillmentWarehouseStock->stock_qty = $pmStock['stockPhysical'];
            $fulfillmentWarehouseStock->reserved_qty = $pmStock['reservedStock'];
            $fulfillmentWarehouseStock->external_updated_at = is_numeric($pmStock['updatedAt']) ? $pmStock['updatedAt'] : strtotime($pmStock['updatedAt']);
            $fulfillmentWarehouseStock->additional = $pmStock;
            $fulfillmentWarehouseStock->stock_pulled_at = $now;
            $fulfillmentWarehouseStock->mustSave(false);
        }
        $pulledExternalItemIds = ArrayHelper::getColumn($pmWarehouseStocks, 'variationId');
        $notPulledExternalItemIds = array_diff($externalItemIds, $pulledExternalItemIds);
        if ($notPulledExternalItemIds) {
            FulfillmentWarehouseStock::deleteAll([
                'fulfillment_account_id' => $this->account->fulfillment_account_id,
                'external_warehouse_id' => $externalWarehouseIds,
                'external_item_id' => $notPulledExternalItemIds,
            ]);
        }
        FulfillmentItem::updateAll(['stock_pulled_at' => $now], [
            'fulfillment_account_id' => $this->account->fulfillment_account_id,
            'external_item_id' => $externalItemIds
        ]);
    }

    #endregion
}
