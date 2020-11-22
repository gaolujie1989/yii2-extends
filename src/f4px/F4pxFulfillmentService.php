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
use yii\base\NotSupportedException;
use yii\di\Instance;

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
     * @var string
     */
    public $externalOrderKeyField = 'consignment_no';

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
        'S' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
        'X' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
        'E' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_PENDING,
        'C' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
    ];

    #endregion

    public $orderProcessingStatus = 'S';
    public $orderCancelledStatus = 'X';
    public $orderErrorStatus = 'E';
    public $orderHoldStatus = 'NONE';
    public $orderShippedStatus = 'C';

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
        if ($fulfillmentItem->external_item_key) {
            return [
                'sku_code' => $item->itemNo,
                'picture_url' => $item->imageUrls,
            ];
        } else {
            return [
                'sku_code' => $item->itemNo,
                'product_code' => '', //$item->getBarcode('EAN'), //@TODO
                'sku_name' => $item->itemName,
                'uom' => 'TAI',
//                'single_sku_code' => '',
//                'pcs' => '',
                'wrapping' => 'H',
                'appearance' => 'RS',
                'weight' => $item->weightG,
                'length' => $item->lengthMM / 10,
                'width' => $item->widthMM / 10,
                'height' => $item->heightMM / 10,
                'logistics_package' => 'Y',
                'package_material' => 'PA',
                'sn_rule_code' => '',
                'expired_date' => 'N',
                'sales_link' => '',
                'include_batch' => 'N',
                'include_battery' => 'N',
//                'battery_config' => '',
//                'battery_type' => '',
//                'battery_power' => '',
//                'battery_number' => '',
//                'battery_resource' => '',
                'picture_url' => $item->imageUrls,
                'remark' => '',
                'release_flag' => 'Y',
                'customer_code' => '',
            ];
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
        $skuList = $this->client->getSkuList(['lstsku' => [$item->itemNo]]);
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
            return $this->client->editSkuPicture($externalItem);
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
        $orderItems = [];
        foreach ($order->orderItems as $orderItem) {
            $orderItems[] = [
                'sku_code' => $orderItem->itemNo,
                'qty' => $orderItem->orderedQty,
                'stock_quality' => 'G',
//                'batch_no' => '',
//                'unit_price' => '',
            ];
        }
        $address = $order->address;
        return [
            'customer_code' => '',
            'ref_no' => 'O-' . $order->orderId,
            'from_warehouse_code' => '', //@TODO
            'consignment_type' => 'S',
            'logistics_service_info' => [
                'logistics_product_code' => '',
                'return_service' => 'N', //@TODO
                'signature_service' => 'N',
            ],
//            'shipping_no' => '',
//            'shippinglabel' => '',
//            'invoice' => '',
//            'msds' => '',
//            'oda' => '',
//            'sales_platform' => '',
//            'seller_id' => '',
            'sales_no' => $order->orderNo,
//            'shop_id' => '',
//            'shop_name' => '',
//            'insure_services' => '',
//            'currency' => '',
//            'insure_value' => '',
            'remark' => $address->additional,
            'oconsignment_desc' => [
                'country' => $address->country,
                'state' => $address->state,
                'city' => $address->city,
                'district' => '',
                'post_code' => $address->postalCode,
                'street' => $address->street,
                'house_number' => $address->houseNo,
                'company' => $address->companyName,
                'last_name' => $address->lastName,
                'first_name' => $address->firstName,
                'phone' => $address->phone,
                'email' => $address->email,
            ],
//            'identity_info' => [
//                'id_type' => '',
//                'id_card' => '',
//                'id_front_pic' => '',
//                'id_back_pic' => '',
//            ],
            'oconsignment_sku' => $orderItems,
//            'icustoms_service' => '',
//            'ocustoms_service' => '',
//            'fba_shipment_id' => '',
//            'oconsignment_fba' => [
//                'fba_box_code' => '',
//                'im_code' => '',
//                'fba_im_quantity' => '',
//                'fba_im_code' => '',
//                'fba_item_label_sign' => '',
//                'fba_label_quantity' => '',
//                'fba_inspect_company' => '',
//                'fba_inspect_company_address' => '',
//                'fba_inspection_logo' => '',
//            ],
        ];
    }

    /**
     * @param Order $order
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalOrder(Order $order): ?array
    {
        $outboundList = $this->client->getOutboundList(['sales_no' => $order->orderNo]);
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
        if ($fulfillmentOrder->external_order_key) {
            return null;
        } else {
            return $this->client->createOutbound($externalOrder);
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $externalOrderStatus = $externalOrder['status'];
        $externalOrderAdditional = $fulfillmentOrder->external_order_additional ?: [];
        $fulfillmentOrder->external_order_key = $externalOrder['consignment_no'];
        $fulfillmentOrder->external_order_status = $externalOrderStatus;
        $fulfillmentOrder->external_created_at = (int)($externalOrder['create_time'] / 1000);
        $fulfillmentOrder->external_updated_at = (int)($externalOrder['update_time'] / 1000);
        $externalOrderAdditional['ref_no'] = $externalOrder['ref_no'];
        $externalOrderAdditional['sales_no'] = $externalOrder['sales_no'];
        $externalOrderAdditional['consignment_no'] = $externalOrder['consignment_no'];
        $externalOrderAdditional['4px_tracking_no'] = $externalOrder['4px_tracking_no'];
        $fulfillmentOrder->external_order_additional = $externalOrderAdditional;

        $statusMap = [
            $this->orderProcessingStatus => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
            $this->orderCancelledStatus => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
            $this->orderErrorStatus => FulfillmentConst::FULFILLMENT_STATUS_SHIP_PENDING,
            $this->orderHoldStatus => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
            $this->orderShippedStatus => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
        ];
        if (isset($statusMap[$externalOrderStatus])) {
            $fulfillmentOrder->fulfillment_status = $statusMap[$externalOrderStatus];
        }
        if ($fulfillmentOrder->fulfillment_status === FulfillmentConst::FULFILLMENT_STATUS_SHIPPED) {
            $externalOrderAdditional['packageNumbers'] = [$externalOrder['shipping_no']];
            $externalOrderAdditional['carrier'] = $externalOrder['logistics_product_code'];
            $externalOrderAdditional['shippingAt'] = (int)($externalOrder['complete_time'] / 1000);
            $fulfillmentOrder->external_order_additional = $externalOrderAdditional;
            if (empty($externalOrderAdditional['packageNumbers'])) {
                $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PROCESSING;
            }
        }

        $fulfillmentOrder->order_pulled_at = time();
        return $fulfillmentOrder->save(false);
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
     * @throws JsonRpcException
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $cancelOutbound = $this->client->cancelOutbound(['consignment_no' => $fulfillmentOrder->external_order_key]);
        $this->updateFulfillmentOrder($fulfillmentOrder, $cancelOutbound);
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
        $externalOrders = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $data = $this->client->getOutboundList(['consignment_no' => $externalOrderKeys]);
            $externalOrders[$externalOrderKey] = $data['data'];
        }
        return $externalOrders;
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
        return $this->client->getWarehouseList($condition);
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
            'service_code' => $externalWarehouse['service_code'],
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
        $eachInboundList = $this->client->eachInboundList(['lstsku' => $externalItemKeys]);
        return iterator_to_array($eachInboundList, false);
    }

    /**
     * @param FulfillmentWarehouseStock $fulfillmentWarehouseStock
     * @param array $externalWarehouseStock
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        $fulfillmentWarehouseStock->stock_qty = $externalWarehouseStock['available_stock'];
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
    protected function getExternalWarehouseStockMovements(FulfillmentWarehouse $fulfillmentWarehouse, int $movementAtFrom, int $movementAtTo, ?FulfillmentItem $fulfillmentItem = null): array
    {
        $condition = [
            'warehouse_code' => $fulfillmentWarehouse->external_warehouse_key,
            'create_time_start' => $fulfillmentWarehouse->external_warehouse_key,
            'create_time_end' => $fulfillmentWarehouse->external_warehouse_key,
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
        $fulfillmentStockMovement->external_created_at = substr($externalStockMovement['create_time'], 0, 10);
        $fulfillmentStockMovement->reason = $externalStockMovement['journal_type'];
        $fulfillmentStockMovement->moved_qty = $externalStockMovement['io_qty'];
        $fulfillmentStockMovement->balance_qty = $externalStockMovement['balance_stock'];
        $fulfillmentStockMovement->related_type = $externalStockMovement['business_type'];
        $fulfillmentStockMovement->related_key = $externalStockMovement['business_ref_no'];
        $fulfillmentStockMovement->movement_additional = [
            'batch_no' => $externalStockMovement['batch_no'],
            'stock_quality' => $externalStockMovement['stock_quality'],
        ];
        return $fulfillmentStockMovement->save(false);
    }

    #endregion

}
