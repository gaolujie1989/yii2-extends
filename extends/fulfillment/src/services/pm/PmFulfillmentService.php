<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\services\pm;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\plentyMarkets\PlentyMarketAddressFormatter;
use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmFulfillmentService extends BaseFulfillmentService
{
    /**
     * @var PlentyMarketsRestClient
     */
    public $client;

    #region External Model Key Field

    /**
     * @var string
     */
    public $externalItemKeyField = 'id';

    /**
     * @var array
     */
    public $externalOrderKeyField = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'id'
    ];

    /**
     * @var array
     */
    public $externalOrderStatusField = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'statusId'
    ];

    /**
     * @var string
     */
    public $externalWarehouseKeyField = 'id';

    /**
     * @var string
     */
    public $stockItemKeyField = 'variationId';

    /**
     * @var string
     */
    public $stockWarehouseKeyField = 'warehouseId';

    /**
     * @var string
     */
    public $externalMovementKeyField = 'id';

    /**
     * @var string
     */
    public $externalMovementTimeField = 'createdAt';

    /**
     * @var array
     */
    public $fulfillmentStatusMap = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => [
            //global
            '4' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
            '5' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '6' => FulfillmentConst::FULFILLMENT_STATUS_PICKING,
            '7' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
            '7.1' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
            '8' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            '8.1' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            //custom
            '4.1' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
            '4.2' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
            '5.1' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '5.2' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '5.4' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '5.5' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '5.6' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '5.7' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            '15.9' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            '5.31' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            '5.32' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            '5.33' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
            '6.5' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
            '6.7' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
            '6.8' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
        ]
    ];

    #endregion

    #region PM custom push field

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
        'EAN' => 1,
        'FNSKU' => 5,
        'OWN_SKU' => 8,
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

    #endregion

    public $variationNoPrefix = 'DIB-';
    public $orderNoPrefix = 'DIB-';
    public $plentyId = 30389;
    public $referrerId = 10;

    public $orderProcessingStatus = 5;
    public $orderProcessingWarehouseId = 0;
    public $orderCancelledStatus = 8;
    public $orderCancelledWarehouseId = 0;
    public $orderErrorStatus = 5.33;
    public $orderHoldStatus = 4;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, PlentyMarketsRestClient::class);
    }

    #region Item Push

    /**
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): ?array
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
     * @param Item $item
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalItem(Item $item): ?array
    {
        foreach ($this->barcodeIds as $barcodeKey => $barcodeId) {
            if ($barcode = $item->getBarcode($barcodeKey)) {
                $eachVariation = $this->client->eachVariations(['barcode' => $barcode]);
                if ($pmVariation = $eachVariation->current()) {
                    return $pmVariation;
                }
            }
        }
        return null;
    }

    /**
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        if ($fulfillmentItem->external_item_key) {
            $externalItem['id'] = $fulfillmentItem->external_item_key;
            $externalItem['itemId'] = $fulfillmentItem->external_item_additional['itemId'];
            return $this->client->updateItemVariation($externalItem);
        } else {
            $pmItemData = array_merge($this->defaultItemData, ['variations' => [$externalItem]]);
            $pmItem = $this->client->createItem($pmItemData);
            return $pmItem['variations'][0];
        }
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        $fulfillmentItem->external_item_key = $externalItem['id'];
        $fulfillmentItem->external_created_at = strtotime($externalItem['createdAt']);
        $fulfillmentItem->external_updated_at = strtotime($externalItem['updatedAt']);
        $fulfillmentItem->external_item_additional = [
            'itemId' => $externalItem['itemId'],
            'variationNo' => $externalItem['number'],
        ];
        return $fulfillmentItem->save(false);
    }

    #endregion

    #region Order Push

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws \Throwable
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if ($fulfillmentOrder->fulfillment_account_id !== $this->account->account_id) {
            Yii::info("FulfillmentOrder account {$fulfillmentOrder->fulfillment_account_id} != account {$this->account->account_id}", __METHOD__);
            return false;
        }

        if ($fulfillmentOrder->fulfillment_type !== FulfillmentConst::FULFILLMENT_TYPE_SHIPPING) {
            throw new NotSupportedException("Fulfillment Type {$fulfillmentOrder->fulfillment_type} not supported by PM");
        }

        /** @var Order $order */
        $order = $this->orderLoader->get($fulfillmentOrder);
        if (empty($order->orderItems)) {
            Yii::info("Empty Order or OrderItems", __METHOD__);
            return false;
        }
        if (empty($fulfillmentOrder->order_pushed_at) && $externalOrder = $this->getExternalOrder($order, $fulfillmentOrder)) {
            Yii::info("Order not pushed, but exist in external, update FulfillmentOrder", __METHOD__);
            $fulfillmentStatus = $this->fulfillmentStatusMap[$fulfillmentOrder->fulfillment_type][$externalOrder['statusId']] ?? null;
            if ($fulfillmentStatus === FulfillmentConst::FULFILLMENT_STATUS_CANCELLED) {
                Yii::info('External Order is exist, but cancelled, set to ship', __METHOD__);
                $externalOrder = $this->client->updateOrder(['id' => $externalOrder['id'], 'statusId' => $this->orderProcessingStatus]);
            }
            $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder, true);
        }

        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        if (empty($externalOrderAdditional) || empty($externalOrderAdditional['customerId'])) {
            Yii::info('Create External Order Customer', __METHOD__);
            $pmCustomer = $this->pushPmCustomer($order->address);
            $externalOrderAdditional['customerId'] = $pmCustomer['id'];
            $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
            $fulfillmentOrder->mustSave(false);
        }
        if (empty($externalOrderAdditional) || empty($externalOrderAdditional['addressId'])) {
            Yii::info('Create External Order Address', __METHOD__);
            $pmAddress = $this->pushPmCustomerAddress((int)$externalOrderAdditional['customerId'], $order->address);
        } else {
            Yii::info('Update External Order Address', __METHOD__);
            $pmAddress = $this->pushPmCustomerAddress((int)$externalOrderAdditional['customerId'], $order->address, (int)$externalOrderAdditional['addressId']);
        }
        $externalOrderAdditional['addressId'] = $pmAddress['id'];
        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
        $fulfillmentOrder->mustSave(false);

        $externalOrder = $this->formatExternalOrderData($order, $fulfillmentOrder);
        if ($externalOrder = $this->saveExternalOrder($externalOrder, $fulfillmentOrder)) {
            Yii::info("Order pushed success, update FulfillmentOrder", __METHOD__);
            return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder, true);
        }
        Yii::warning("Order pushed failed, skip update FulfillmentOrder", __METHOD__);
        return false;
    }

    /**
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array
     * @inheritdoc
     */
    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        $externalOrderAdditional = $fulfillmentOrder->external_order_additional;
        if ($fulfillmentOrder->external_order_key) {
            return [
                'addressRelations' => [
                    [
                        'typeId' => PlentyMarketsConst::ADDRESS_TYPE_IDS['billing'],
                        'addressId' => $externalOrderAdditional['addressId'],
                    ],
                    [
                        'typeId' => PlentyMarketsConst::ADDRESS_TYPE_IDS['delivery'],
                        'addressId' => $externalOrderAdditional['addressId'],
                    ],
                ],
            ];
        } else {
            $orderItems = [];
            foreach ($order->orderItems as $orderItem) {
                $orderItems[] = $this->formatExternalOrderItemData($orderItem);
            }
            return [
                'typeId' => 1,
                'plentyId' => $this->plentyId,
                'statusId' => $this->orderProcessingStatus,
                'orderItems' => $orderItems,
                'properties' => [
                    [
                        'typeId' => PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['PAYMENT_METHOD'],
                        'value' => '14',
                    ],
                    [
                        'typeId' => PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['PAYMENT_STATUS'],
                        'value' => 'fullyPaid',
                    ],
                    [
                        'typeId' => PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['EXTERNAL_ORDER_ID'],
                        'value' => $this->orderNoPrefix . $order->orderNo,
                    ],
                ],
                'addressRelations' => [
                    [
                        'typeId' => PlentyMarketsConst::ADDRESS_TYPE_IDS['billing'],
                        'addressId' => $externalOrderAdditional['addressId'],
                    ],
                    [
                        'typeId' => PlentyMarketsConst::ADDRESS_TYPE_IDS['delivery'],
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
    }

    /**
     * @param OrderItem $orderItem
     * @return array
     * @inheritdoc
     */
    protected function formatExternalOrderItemData(OrderItem $orderItem): array
    {
        $fulfillmentItem = FulfillmentItem::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->itemId($orderItem->itemId)
            ->one();
        if ($fulfillmentItem === null) {
            throw new InvalidArgumentException('FulfillmentItem not exist');
        }
        return [
            'typeId' => 1,
            'referrerId' => $this->referrerId,
            'itemVariationId' => $fulfillmentItem->external_item_key,
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
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalOrder(Order $order, FulfillmentOrder $fulfillmentOrder): ?array
    {
        $externalOrderId = $this->orderNoPrefix . $order->orderNo;
        $eachOrder = $this->client->eachOrders([
            'externalOrderId' => $externalOrderId,
//            'plentyId' => $this->plentyId,
            'with' => 'addresses'
        ]);
        if ($pmOrder = $eachOrder->current()) {
            return $pmOrder;
        }
        return null;
    }

    /**
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        if (empty($fulfillmentOrder->external_order_key)) {
            return $pmOrder = $this->client->createOrder($externalOrder);
        } else {
            $externalOrder['id'] = $fulfillmentOrder->external_order_key;
            return $this->client->updateOrder($externalOrder);
        }
    }

    /**
     * @param Address $address
     * @return array
     * @inheritdoc
     */
    protected function pushPmCustomer(Address $address): array
    {
        if ($address->phone) {
            $customers = $this->client->eachCustomers(['privatePhone' => $address->phone]);
            if ($customer = $customers->current()) {
                return $customer;
            }
        }
        if ($address->email) {
            $customers = $this->client->eachCustomers(['email' => $address->email]);
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
        $countryId = $countryCodeToId[$address->country] ?? false;
        if (empty($countryId)) {
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
            'countryId' => $countryId,
        ];

        $addressData = PlentyMarketAddressFormatter::format($addressData);

        $addressOptions = [];
        if ($address->phone) {
            $addressOptions[] = [
                'typeId' => PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Telephone'],
                'value' => (string)$address->phone,
            ];
        }
        if ($address->email) {
            $addressOptions[] = [
                'typeId' => PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Email'],
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
     * @param array $externalOrder
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        $externalOrderStatus = $externalOrder['statusId'];
        $pmOrderProperties = ArrayHelper::map($externalOrder['properties'], 'typeId', 'value');
        $pmOrderDates = ArrayHelper::map($externalOrder['dates'], 'typeId', 'value');
        $fulfillmentOrder->external_created_at = is_numeric($externalOrder['createdAt']) ? $externalOrder['createdAt'] : strtotime($externalOrder['createdAt']);
        $fulfillmentOrder->external_updated_at = is_numeric($externalOrder['updatedAt']) ? $externalOrder['updatedAt'] : strtotime($externalOrder['updatedAt']);
        $externalOrderAdditional['externalOrderNo'] = $pmOrderProperties[7] ?? '';
        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;

        if ($externalOrderStatus >= 7 && $externalOrderStatus < 8) {
            $externalOrderAdditional['shippingProfileId'] = $pmOrderProperties[2] ?? 0;
            $externalOrderAdditional['carrier'] = $this->shippingProfiles[$externalOrderAdditional['shippingProfileId']] ?? '';
            $externalOrderAdditional['shippedAt'] = isset($pmOrderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']])
                ? strtotime($pmOrderDates[PlentyMarketsConst::ORDER_DATE_TYPE_IDS['OutgoingItemsBookedOn']]) : 0;

            $packageNumbers = $externalOrder['packageNumbers'] ?? [];
            if (empty($packageNumbers)) {
                $packageNumbers = $this->client->getOrderPackageNumbers(['orderId' => $externalOrder['id']]);
            }
            $externalOrderAdditional['trackingNumbers'] = $packageNumbers;
            $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
        }

        return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, $changeActionStatus);
    }

    #endregion

    #region Order Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if (empty($fulfillmentOrder->external_order_key)) {
            $pmOrder = [
                'id' => '',
                'statusId' => $this->orderHoldStatus
            ];
            parent::updateFulfillmentOrder($fulfillmentOrder, $pmOrder, true);
            return true;
        }

        $pmOrder = $this->client->getOrder(['id' => $fulfillmentOrder->external_order_key]);
        if ($this->isOrderAllowHolding($pmOrder)) {
            $pmOrder = $this->client->updateOrder(['id' => $fulfillmentOrder->external_order_key, 'statusId' => $this->orderHoldStatus]);
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder, true);
        return $pmOrder['statusId'] === $this->orderHoldStatus;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $pmOrderId = $fulfillmentOrder->external_order_key;
        if (empty($pmOrderId)) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
        }
        if (empty($pmOrderId)) {
            return false;
        }
        $pmOrder = $this->client->getOrder(['id' => $pmOrderId]);
        if ($this->isOrderAllowShipping($pmOrder)) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
            $pmOrder = $this->client->updateOrder(['id' => $pmOrderId, 'statusId' => $this->orderProcessingStatus]);
            if ($pmOrder['statusId'] === $this->orderProcessingStatus && $this->orderProcessingWarehouseId) {
                $this->client->updateOrderWarehouse($pmOrderId, $this->orderProcessingWarehouseId, false);
            }
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder, true);
        return $pmOrder['statusId'] === $this->orderProcessingStatus;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $pmOrderId = (int)$fulfillmentOrder->external_order_key;
        if (empty($pmOrderId)) {
            $pmOrder = [
                'id' => '',
                'statusId' => $this->orderCancelledStatus
            ];
            parent::updateFulfillmentOrder($fulfillmentOrder, $pmOrder, true);
            return true;
        }

        $pmOrder = $this->client->getOrder(['id' => $pmOrderId]);
        if ($this->isOrderAllowCancelled($pmOrder)) {
            $pmOrder = $this->client->updateOrder(['id' => $pmOrderId, 'statusId' => $this->orderCancelledStatus]);
            if ($pmOrder['statusId'] === $this->orderCancelledStatus && $this->orderCancelledWarehouseId) {
                $this->client->updateOrderWarehouse($pmOrderId, $this->orderCancelledWarehouseId, false);
            }
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $pmOrder, true);
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
        return ($pmStatusId >= 4 && $pmStatusId < 6) || in_array($pmStatusId, [6.5, 6.7, 6.8], true);
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
        $pmOrders = $this->client->getOrdersByOrderIds($externalOrderKeys);
        $pmOrderPackageNumbers = $this->client->getOrderPackageNumbersByOrderIds($externalOrderKeys);
        foreach ($pmOrders as $pmOrderId => $pmOrder) {
            $pmOrders[$pmOrderId]['packageNumbers'] = $pmOrderPackageNumbers[$pmOrderId] ?? [];
        }
        return $pmOrders;
    }

    /**
     * @param int $shippedAtFrom
     * @param int $shippedAtTo
     * @return array
     * @inheritdoc
     */
    protected function getShippedExternalOrders(int $shippedAtFrom, int $shippedAtTo): array
    {
        $condition = [
            'outgoingItemsBookedAtFrom' => date('c', $shippedAtFrom),
            'outgoingItemsBookedAtTo' => date('c', $shippedAtTo),
            'statusFrom' => '7',
            'statusTo' => '7.1',
        ];
        $eachOrders = $this->client->eachOrders($condition);
        $pmOrders = iterator_to_array($eachOrders, false);
        $pmOrderPackageNumbers = $this->client->getOrderPackageNumbersByOrderIds(ArrayHelper::getColumn($pmOrders, 'id'));
        foreach ($pmOrders as $pmOrderId => $pmOrder) {
            $pmOrders[$pmOrderId]['packageNumbers'] = $pmOrderPackageNumbers[$pmOrderId] ?? [];
        }
        return $pmOrders;
    }

    #endregion

    #region Warehouse Pull

    /**
     * @param array $condition
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouses(array $condition = []): array
    {
        $eachWarehouses = $this->client->eachWarehouses($condition);
        return iterator_to_array($eachWarehouses, false);
    }

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param array $externalWarehouse
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        $fulfillmentWarehouse->external_warehouse_additional = ['name' => $externalWarehouse['name']];
        return $fulfillmentWarehouse->save(false);
    }

    #endregion

    #region Stock Pull

    /**
     * @param array $externalItemKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouseStocks(array $externalItemKeys): array
    {
        return $this->client->getWarehouseStocksByVariationIds($externalItemKeys);
    }

    /**
     * @param FulfillmentWarehouseStock $fulfillmentWarehouseStock
     * @param array $externalWarehouseStock
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        $fulfillmentWarehouseStock->stock_qty = $externalWarehouseStock['stockPhysical'];
        $fulfillmentWarehouseStock->reserved_qty = $externalWarehouseStock['reservedStock'];
        $fulfillmentWarehouseStock->external_updated_at = is_numeric($externalWarehouseStock['updatedAt'])
            ? $externalWarehouseStock['updatedAt']
            : strtotime($externalWarehouseStock['updatedAt']);
        $fulfillmentWarehouseStock->stock_additional = $externalWarehouseStock;
        return $fulfillmentWarehouseStock->save(false);
    }

    #endregion

    #region Stock Movement Pull

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param int $movementAtFrom
     * @param int $movementAtTo
     * @param FulfillmentItem|null $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouseStockMovements(
        FulfillmentWarehouse $fulfillmentWarehouse,
        int $movementAtFrom,
        int $movementAtTo,
        ?FulfillmentItem $fulfillmentItem = null
    ): array
    {
        $condition = [
            'warehouseId' => $fulfillmentWarehouse->external_warehouse_key,
            'createdAtFrom' => date('c', $movementAtFrom),
            'createdAtTo' => date('c', $movementAtTo),
        ];
        if (time() - $movementAtFrom > 86400 * 90) {
            $condition['year'] = date('Y', $movementAtFrom);
        }
        if ($fulfillmentItem !== null) {
            $condition['variationId'] = $fulfillmentItem->external_item_key;
        }
        $eachWarehouseStockMovements = $this->client->eachWarehouseStockMovements($condition);
        return iterator_to_array($eachWarehouseStockMovements, false);
    }

    /**
     * @param FulfillmentWarehouseStockMovement $fulfillmentStockMovement
     * @param array $externalStockMovement
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStockMovements(
        FulfillmentWarehouseStockMovement $fulfillmentStockMovement,
        array $externalStockMovement
    ): bool
    {
        $fulfillmentStockMovement->external_created_at = is_numeric($externalStockMovement['createdAt'])
            ? $externalStockMovement['createdAt']
            : strtotime($externalStockMovement['createdAt']);
        if ($externalStockMovement['reason'] < 200) {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_INBOUND;
        } elseif ($externalStockMovement['reason'] < 300) {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_OUTBOUND;
        } else {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_CORRECTION;
        }
        $fulfillmentStockMovement->movement_qty = $externalStockMovement['quantity'];
        $fulfillmentStockMovement->related_type = $externalStockMovement['processRowType'];
        $fulfillmentStockMovement->related_key = $externalStockMovement['processRowId'];
        $fulfillmentStockMovement->movement_additional = [
            'storageLocationName' => $externalStockMovement['storageLocationName'],
            'reason' => $externalStockMovement['reason'],
        ];
        return $fulfillmentStockMovement->save(false);
    }

    #endregion

    #region Charging Pull

    public function pullFulfillmentCharges(array $fulfillmentOrders): void
    {
    }

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalCharges(array $externalOrderKeys): array
    {
        return [];
    }

    #endregion
}
