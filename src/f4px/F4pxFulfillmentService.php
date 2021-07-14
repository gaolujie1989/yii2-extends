<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\f4px;

use lujie\extend\authclient\JsonRpcException;
use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class F4pxFulfillmentService extends BaseFulfillmentService
{
    /**
     * @var F4pxClient
     */
    public $client;

    #region External Model Key Field

    /**
     * @var string
     */
    public $externalItemKeyField = 'sku_id';

    /**
     * @var array
     */
    public $externalOrderKeyField = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'consignment_no',
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => 'consignment_no',
    ];

    /**
     * @var array
     */
    public $externalOrderStatusField = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'status',
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => 'status',
    ];

    /**
     * @var string
     */
    public $externalWarehouseKeyField = 'warehouse_code';

    /**
     * @var string
     */
    public $stockItemKeyField = 'sku_id';

    /**
     * @var string
     */
    public $stockWarehouseKeyField = 'warehouse_code';

    /**
     * @var string
     */
    public $externalMovementKeyField = 'inventory_flow_id';

    /**
     * @var string
     */
    public $externalMovementTimeField = 'create_time';

    /**
     * @var array
     */
    public $fulfillmentStatusMap = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => [
            'S' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            'P' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            'X' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            'E' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
            'C' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
        ],
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => [
            'G' => FulfillmentConst::INBOUND_STATUS_ARRIVED,
            'V' => FulfillmentConst::INBOUND_STATUS_RECEIVED,
            'K' => FulfillmentConst::INBOUND_STATUS_RECEIVED,
            'P' => FulfillmentConst::INBOUND_STATUS_RECEIVED,
            'C' => FulfillmentConst::INBOUND_STATUS_INBOUNDED,
            'E' => FulfillmentConst::INBOUND_STATUS_INBOUND_ERROR,
            'X' => FulfillmentConst::INBOUND_STATUS_CANCELLED,
        ],
    ];

    #endregion

    #region F4PX custom push field

    /**
     * @var array
     */
    public $defaultItemData = [
        'uom' => 'EAH',
        'wrapping' => 'H',
        'appearance' => 'RS',
        'logistics_package' => 'Y',
        'package_material' => 'PA',
        'sn_rule_code' => '',
        'expired_date' => 'N',
        'sales_link' => '',
        'include_batch' => 'N',
        'include_battery' => 'N',
        'remark' => '',
        'release_flag' => 'Y',
        'customer_code' => '',
    ];

    public $defaultOrderData = [
        'customer_code' => '',
        'consignment_type' => 'S',
        'logistics_service_info' => [
            'logistics_product_code' => '',
            'return_service' => 'N',
            'signature_service' => 'N',
        ],
    ];

    public $defaultOrderItemData = [
        'stock_quality' => 'G',
//        'batch_no' => '',
//        'unit_price' => '',
    ];

    public $defaultInboundData = [
        'customer_code' => '',
        'business_type' => 'F',
        'is_pickup' => 'N',
        'transport_type' => 'S',
        'tracking_no' => '',
        'logistics_service_info' => [
            'logistics_product_code' => '',
            'signature_service' => 'N',
            'insure_services' => '',
        ],
        'ocustoms_service' => 'D1',
        'icustoms_service' => 'D4',
        'currency' => 'EUR',
        'print_box_no' => 'Y',
        'print_box_type' => 'Z',
    ];

    public $defaultInboundItemData = [];

    #endregion

    public $orderProcessingStatus = 'S';
    public $orderCancelledStatus = 'X';
    public $orderErrorStatus = 'E';
    public $orderHoldStatus = 'NONE';
    public $orderShippedStatus = 'C';

    /**
     * @var array
     */
    public $logisticsCarriers = [
        'F040' => 'TR72',
        'F307' => 'GLS-BIG',
        'F319' => 'GLS-SMALL',
        'F341' => 'GRS-FB4-IMS',
        'F414' => 'GLS-ASM',
        'F430' => 'ASM-FBA',
        'F431' => 'ASM-24',
        'F474' => 'UPS',
        'F475' => 'UPS-FBA',
        'F532' => 'DHL',
    ];

    /**
     * @var array
     */
    public $chargeTypes = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, F4pxClient::class);
    }

    #region Item Push

    /**
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array
    {
        $pictureUrls = array_slice($item->imageUrls, 0, 6);
        $pictureUrls = array_map(static function ($url) {
            return $url . '#xxx.jpg';
        }, $pictureUrls);
        if ($fulfillmentItem->external_item_key) {
            return [
                'sku_code' => strtoupper($item->itemNo),
                'picture_url' => $pictureUrls,
            ];
        } else {
            return array_merge($this->defaultItemData, [
                'sku_code' => strtoupper($item->itemNo),
                'product_code' => $item->getBarcode('EAN') ?: $item->getBarcode('OWN') ?: '',
                'sku_name' => $item->itemName,
                'weight' => $item->weightG,
                'length' => $item->lengthMM / 10,
                'width' => $item->widthMM / 10,
                'height' => $item->heightMM / 10,
                'sales_link' => $item->salesUrl,
                'picture_url' => $pictureUrls,
            ]);
        }
    }

    /**
     * @param Item $item
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalItem(Item $item): ?array
    {
        $skuList = $this->client->getSkuList(['lstsku' => [strtoupper($item->itemNo)]]);
        return $skuList['skulist'][0] ?? null;
    }

    /**
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        if ($fulfillmentItem->external_item_key) {
            $skuList = $this->client->getSkuList(['lstsku' => [$fulfillmentItem->external_item_additional['sku_code']]]);
            $sku = $skuList['skulist'][0] ?? null;
            if ($sku !== null && empty($sku['picture_url'])) {
                $this->client->editSkuPicture($externalItem);
            }
            return $sku;
        } else {
            return $this->client->createSku($externalItem);
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
        $fulfillmentItem->external_item_key = $externalItem['sku_id'];
        $fulfillmentItem->external_item_additional = ['sku_code' => $externalItem['sku_code']];
        if (empty($fulfillmentItem->external_created_at)) {
            $fulfillmentItem->external_created_at = time();
        }
        if (empty($fulfillmentItem->external_updated_at)) {
            $fulfillmentItem->external_updated_at = time();
        }
        return $fulfillmentItem->save(false);
    }

    #endregion

    #region Order Push

    /**
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array
     * @inheritdoc
     */
    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        if ($fulfillmentOrder->fulfillment_type === FulfillmentConst::FULFILLMENT_TYPE_INBOUND) {
            return $this->formatExternalInboundData($order, $fulfillmentOrder);
        }
        $orderItems = [];
        $fulfillmentItems = FulfillmentItem::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->itemId(ArrayHelper::getColumn($order->orderItems, 'itemId'))
            ->indexBy('item_id')
            ->all();
        foreach ($order->orderItems as $orderItem) {
            if (empty($fulfillmentItems[$orderItem->itemId])) {
                throw new InvalidArgumentException('FulfillmentItem not exist');
            }
            $fulfillmentItem = $fulfillmentItems[$orderItem->itemId];
            $orderItems[] = array_merge($this->defaultOrderItemData, [
                'sku_code' => $fulfillmentItem->external_item_additional['sku_code'],
                'qty' => $orderItem->orderedQty,
            ]);
        }
        $address = $order->address;
        if (empty($address->firstName)) {
            $address->firstName = $address->lastName;
            $address->lastName = '';
        }
        return ArrayHelper::merge($this->defaultOrderData, [
            'ref_no' => 'FO-' . $fulfillmentOrder->fulfillment_order_id,
            'from_warehouse_code' => $fulfillmentOrder->external_warehouse_key,
            'sales_no' => $order->orderNo,
            'oconsignment_desc' => [
                'country' => $address->country,
                'state' => $address->state,
                'city' => $address->city,
                'district' => '',
                'post_code' => $address->postalCode,
                'street' => $address->street ?: $address->companyName,
                'house_number' => $address->houseNo . (trim($address->additional) ? ' ' . $address->additional : ''),
                'company' => $address->companyName,
                'last_name' => $address->lastName,
                'first_name' => $address->firstName,
                'phone' => $address->phone ?: '000-0000-0000',
                'email' => $address->email,
            ],
            'oconsignment_sku' => $orderItems,
        ]);
    }

    /**
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalOrder(Order $order, FulfillmentOrder $fulfillmentOrder): ?array
    {
        if ($order->fulfillmentType === FulfillmentConst::FULFILLMENT_TYPE_INBOUND) {
            return $this->getExternalInbound($order);
        }
        $outboundList = $this->client->getOutboundList(['ref_no' => 'FO-' . $fulfillmentOrder->fulfillment_order_id]);
        return $outboundList['data'][0] ?? null;
    }

    /**
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        if ($fulfillmentOrder->fulfillment_type === FulfillmentConst::FULFILLMENT_TYPE_INBOUND) {
            return $this->saveExternalInbound($externalOrder, $fulfillmentOrder);
        }
        if ($fulfillmentOrder->external_order_key) {
            return null;
        } else {
            return $this->client->createOutbound($externalOrder);
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        try {
            return parent::pushFulfillmentOrder($fulfillmentOrder);
        } catch (JsonRpcException $exception) {
            if (strpos($exception->getMessage(), '当前产品的目的地不支持该邮编') !== false) {
                $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR;
                $fulfillmentOrder->additional = array_merge($fulfillmentOrder->additional ?: [], ['error' => '当前产品的目的地不支持该邮编']);
                $externalOrder = [
                    'consignment_no' => '',
                    'status' => '',
                ];
                return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, true);
            }
            throw $exception;
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        if ($fulfillmentOrder->fulfillment_type === FulfillmentConst::FULFILLMENT_TYPE_INBOUND) {
            return $this->updateFulfillmentInbound($fulfillmentOrder, $externalOrder, $changeActionStatus);
        }

        if (empty($externalOrder['status'])) {
            $externalOrder['status'] = 'S';
            return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, $changeActionStatus);
        }
        if (empty($externalOrder['create_time'])) {
            return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, $changeActionStatus);
        }

        $externalOrderStatus = $externalOrder['status'];
        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        $fulfillmentOrder->external_created_at = (int)($externalOrder['create_time'] / 1000);
        $fulfillmentOrder->external_updated_at = (int)($externalOrder['update_time'] / 1000);
        $externalOrderAdditional['ref_no'] = $externalOrder['ref_no'];
        $externalOrderAdditional['sales_no'] = $externalOrder['sales_no'];
        $externalOrderAdditional['consignment_no'] = $externalOrder['consignment_no'];
        $externalOrderAdditional['4px_tracking_no'] = $externalOrder['4px_tracking_no'];
        $externalOrderAdditional['logistics_product_code'] = $externalOrder['logistics_product_code'];
        $externalOrderAdditional['carrier'] = $this->logisticsCarriers[$externalOrder['logistics_product_code']] ?? '';
        if ($externalOrder['shipping_no']) {
            $externalOrderAdditional['trackingNumbers'] = [$externalOrder['shipping_no']];
        }

        if ($externalOrderStatus === $this->orderShippedStatus) {
            $externalOrderAdditional['shippedAt'] = (int)($externalOrder['complete_time'] / 1000);
        }

        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
        return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, $changeActionStatus);
    }

    #endregion

    #region Order Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        throw new NotSupportedException('F4px not support order holding');
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        throw new NotSupportedException('F4px not support order shipping');
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if ($fulfillmentOrder->external_order_key) {
            $outboundList = $this->client->getOutboundList(['consignment_no' => $fulfillmentOrder->external_order_key]);
            $cancelOutbound = $outboundList['data'][0];
            if ($cancelOutbound['status'] !== 'X') {
                $cancelOutbound = $this->client->cancelOutbound(['consignment_no' => $fulfillmentOrder->external_order_key, 'cancel_type' => 'BC']);
                sleep(1); //wait cancelled
                $outboundList = $this->client->getOutboundList(['consignment_no' => $fulfillmentOrder->external_order_key]);
                $cancelOutbound = $outboundList['data'][0];
            }
        } else {
            $cancelOutbound = [
                'consignment_no' => '',
                'status' => 'X',
            ];
        }
        $this->updateFulfillmentOrder($fulfillmentOrder, $cancelOutbound, true);
        return $cancelOutbound['status'] === 'X';
    }

    #endregion

    #region Order Pull

    /**
     * @param array $externalOrderKeys
     * @return array
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        $one = FulfillmentOrder::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalOrderKey($externalOrderKeys)
            ->select([
                'MIN(external_created_at) AS min_created_at',
                'MAX(external_created_at) AS max_created_at',
                'MIN(order_pushed_at) AS min_pushed_at',
                'MAX(order_pushed_at) AS max_pushed_at',
            ])
            ->asArray()
            ->one();
        $createdAtFrom = $one['min_created_at'] ?: $one['min_pushed_at'];
        $createdAtTo = $one['max_created_at'] ?: $one['max_pushed_at'];
        $createdAtFrom -= 10;
        $createdAtTo += 10;
        if ($createdAtFrom <= $createdAtTo - 86400 * 25) {
            $createdAtFrom = $createdAtTo - 86400 * 25;
        }
        $processingOutbounds = $this->client->eachOutboundList([
            'create_time_start' => $createdAtFrom * 1000,
            'create_time_end' => $createdAtTo * 1000,
            'status' => 'S',
        ]);
        $pickingOutbounds = $this->client->eachOutboundList([
            'create_time_start' => $createdAtFrom * 1000,
            'create_time_end' => $createdAtTo * 1000,
            'status' => 'P',
        ]);
        $cancelledOutbounds = $this->client->eachOutboundList([
            'create_time_start' => $createdAtFrom * 1000,
            'create_time_end' => $createdAtTo * 1000,
            'status' => 'X',
        ]);
        $errorOutbounds = $this->client->eachOutboundList([
            'create_time_start' => $createdAtFrom * 1000,
            'create_time_end' => $createdAtTo * 1000,
            'status' => 'E',
        ]);
        $externalOrders = array_merge(
            iterator_to_array($processingOutbounds, false),
            iterator_to_array($pickingOutbounds, false),
            iterator_to_array($cancelledOutbounds, false),
            iterator_to_array($errorOutbounds, false),
        );
        $externalOrders = ArrayHelper::index($externalOrders, 'consignment_no');
        return array_intersect_key($externalOrders, array_flip($externalOrderKeys));
        //code above will cause api rate limit
//        $externalOrders = [];
//        foreach ($externalOrderKeys as $externalOrderKey) {
//            $data = $this->client->getOutboundList(['consignment_no' => $externalOrderKey]);
//            $externalOrders[$externalOrderKey] = $data['data'][0] ?? [];
//        }
//        return $externalOrders;
    }

    /**
     * @param int $shippedAtFrom
     * @param int $shippedAtTo
     * @return array
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getShippedExternalOrders(int $shippedAtFrom, int $shippedAtTo): array
    {
        $condition = [
            'status' => 'C',
            'complete_time_start' => $shippedAtFrom * 1000,
            'complete_time_end' => $shippedAtTo * 1000,
        ];
        $eachOutboundList = $this->client->eachOutboundList($condition);
        return iterator_to_array($eachOutboundList, false);
    }

    #endregion

    #region Warehouse Stock Pull

    /**
     * @param array $condition
     * @return array
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalWarehouses(array $condition = []): array
    {
        $externalWarehouses = $this->client->getWarehouseList($condition);
        if (isset($condition['country'])) {
            return array_filter($externalWarehouses, static function ($externalWarehouse) use ($condition) {
                return $externalWarehouse['country'] === $condition['country'];
            });
        }
        return $externalWarehouses;
    }

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param array $externalWarehouse
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        $fulfillmentWarehouse->external_warehouse_additional = [
            'name_cn' => $externalWarehouse['warehouse_name_cn'],
            'name_en' => $externalWarehouse['warehouse_name_en'],
            'country' => $externalWarehouse['country'],
            'service_code' => $externalWarehouse['service_code'] ?? null,
        ];
        return $fulfillmentWarehouse->save(false);
    }

    /**
     * @param array $externalItemKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouseStocks(array $externalItemKeys): array
    {
        $fulfillmentItems = FulfillmentItem::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalItemKey($externalItemKeys)
            ->all();
        $skuCodes = ArrayHelper::map($fulfillmentItems, 'external_item_key', 'external_item_additional.sku_code');
        $eachInventory = $this->client->eachInventory(['lstsku' => array_values($skuCodes)]);
        return iterator_to_array($eachInventory, false);
    }

    /**
     * @param FulfillmentWarehouseStock $fulfillmentWarehouseStock
     * @param array $externalWarehouseStock
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        $fulfillmentWarehouseStock->stock_qty = $externalWarehouseStock['available_stock'] + $externalWarehouseStock['pending_stock'];
        $fulfillmentWarehouseStock->reserved_qty = $externalWarehouseStock['pending_stock'];
        $fulfillmentWarehouseStock->stock_additional = [
            'onway_stock' => $externalWarehouseStock['onway_stock'],
            'stock_quality' => $externalWarehouseStock['stock_quality'],
            'batch_no' => $externalWarehouseStock['batch_no'],
        ];
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
            'warehouse_code' => $fulfillmentWarehouse->external_warehouse_key,
            'create_time_start' => $movementAtFrom * 1000,
            'create_time_end' => $movementAtTo * 1000,
        ];
        if ($fulfillmentItem !== null) {
            $condition['sku_code'] = $fulfillmentItem->external_item_key;
        }
        $eachInventoryLog = $this->client->eachInventoryLog($condition);
        return iterator_to_array($eachInventoryLog, false);
    }

    /**
     * @param FulfillmentWarehouseStockMovement $fulfillmentStockMovement
     * @param array $externalStockMovement
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStockMovements(FulfillmentWarehouseStockMovement $fulfillmentStockMovement, array $externalStockMovement): bool
    {
        $fulfillmentStockMovement->external_created_at = (int)substr($externalStockMovement['create_time'], 0, 10);
        if ($externalStockMovement['journal_type'] === 'I') {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_INBOUND;
        } elseif ($externalStockMovement['journal_type'] === 'O') {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_OUTBOUND;
        } else {
            $fulfillmentStockMovement->movement_type = FulfillmentConst::MOVEMENT_TYPE_CORRECTION;
        }
        $fulfillmentStockMovement->movement_qty = $externalStockMovement['io_qty'];
        $fulfillmentStockMovement->related_type = $externalStockMovement['business_type'];
        $fulfillmentStockMovement->related_key = $externalStockMovement['business_ref_no'];
        $fulfillmentStockMovement->movement_additional = [
            'batch_no' => $externalStockMovement['batch_no'],
            'balance_stock' => $externalStockMovement['balance_stock'],
            'stock_quality' => $externalStockMovement['stock_quality'],
            'journal_type' => $externalStockMovement['journal_type'],
        ];
        return $fulfillmentStockMovement->save(false);
    }

    #endregion

    #region Inbound Push

    /**
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array
     * @inheritdoc
     */
    protected function formatExternalInboundData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        $orderItems = [];
        foreach ($order->orderItems as $orderItem) {
            $orderItems[] = array_merge($this->defaultInboundItemData, [
                'sku_code' => strtoupper($orderItem->itemNo),
                'plan_qty' => $orderItem->orderedQty,
            ]);
        }
        return ArrayHelper::merge($this->defaultInboundData, [
            'ref_no' => $order->orderNo,
            'from_warehouse_code' => $fulfillmentOrder->external_warehouse_key,
            'to_warehouse_code' => $fulfillmentOrder->external_warehouse_key,
            'total_volume' => round($order->totalVolumeMM3 / 1000000) / 1000,
            'total_weight' => $order->totalWeightG / 1000,
            'remark' => $order->additional['note'] ?? '',
            'iconsignment_sku' => $orderItems
        ]);
    }

    /**
     * @param Order $order
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalInbound(Order $order): ?array
    {
        $outboundList = $this->client->getInboundList(['ref_no' => 'I-' . $order->orderId]);
        return $outboundList['data'][0] ?? null;
    }

    /**
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalInbound(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        if ($fulfillmentOrder->external_order_key) {
            return null;
        } else {
            return $this->client->createInbound($externalOrder);
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @param bool $changeActionStatus
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function updateFulfillmentInbound(FulfillmentOrder $fulfillmentOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        $fulfillmentOrder->external_created_at = (int)($externalOrder['create_time'] / 1000);
        $fulfillmentOrder->external_updated_at = (int)($externalOrder['update_time'] / 1000);
        $externalOrderAdditional['ref_no'] = $externalOrder['ref_no'];
        $externalOrderAdditional['consignment_no'] = $externalOrder['consignment_no'];
        $externalOrderAdditional['4px_tracking_no'] = $externalOrder['4px_tracking_no'];
        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;

        return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder, $changeActionStatus);
    }

    #endregion

    #region Inbound Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function cancelFulfillmentInbound(FulfillmentOrder $fulfillmentOrder): bool
    {
        $cancelOutbound = $this->client->cancelInbound(['consignment_no' => $fulfillmentOrder->external_order_key]);
        $this->updateFulfillmentOrder($fulfillmentOrder, $cancelOutbound, true);
        return $cancelOutbound['status'] === 'X';
    }

    #endregion

    #region Inbound Pull

    /**
     * @param array $externalOrderKeys
     * @return array
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalInbounds(array $externalOrderKeys): array
    {
        $externalOrders = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $data = $this->client->getInboundList(['consignment_no' => $externalOrderKeys]);
            $externalOrders[$externalOrderKey] = $data['data'];
        }
        return $externalOrders;
    }

    #endregion


    #region Charging Pull

    /**
     * @param FulfillmentOrder[] $fulfillmentOrders
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function pullFulfillmentCharges(array $fulfillmentOrders): void
    {
        $fulfillmentOrders = array_filter($fulfillmentOrders, static function($fulfillmentOrder) {
            return $fulfillmentOrder->external_order_status === 'C' || $fulfillmentOrder->external_order_status === 'X';
        });
        if ($fulfillmentOrders) {
            parent::pullFulfillmentCharges($fulfillmentOrders);
        }
    }

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalCharges(array $externalOrderKeys): array
    {
        $externalCharges = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $data = ['order_no' => $externalOrderKey, 'business_type' => $externalOrderKey[0]];
            $externalCharges[$externalOrderKey] = $this->client->getBilling($data);
        }
        return $externalCharges;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrderCharges
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    protected function updateFulfillmentCharge(FulfillmentOrder $fulfillmentOrder, array $externalOrderCharges): array
    {
        $billingTypes = ArrayHelper::getColumn($externalOrderCharges, 'billing_type');
        $undefinedBillingTypes = array_diff($billingTypes, array_keys($this->chargeTypes));
        if ($undefinedBillingTypes) {
            $undefinedBillingTypes = implode(',', $undefinedBillingTypes);
            $message = "Undefined billing type {$undefinedBillingTypes} of fulfillment order {$fulfillmentOrder->external_order_key}";
            throw new InvalidArgumentException($message);
        }
        foreach ($externalOrderCharges as $key => $externalOrderCharge) {
            $externalOrderCharges[$key] = [
                'charge_type' => $externalOrderCharge['billing_type'],
                'charged_at' => $externalOrderCharge['billing_date'] / 1000,
                'currency' => $externalOrderCharge['currency'],
                'price_cent' => $externalOrderCharge['billing_amount'] * 100,
            ];
        }
        return parent::updateFulfillmentCharge($fulfillmentOrder, $externalOrderCharges);
    }

    #endregion
}
