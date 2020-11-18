<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\f4px;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
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
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    protected function getExternalItem(Item $item): ?array
    {
        $skuList = $this->client->getSkuList(['lstsku' => [$item->itemNo]])->getData();
        return $skuList['skulist'][0] ?? null;
    }

    /**
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        if ($fulfillmentItem->external_item_key) {
            return $this->client->editSkuPicture($externalItem)->getData();
        } else {
            return $this->client->createSku($externalItem)->getData();
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
            'ref_no' => $order->orderId,
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
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    protected function getExternalOrder(Order $order): ?array
    {
        $outboundList = $this->client->getOutboundList(['sales_no' => $order->orderNo])->getData();
        return $outboundList['data'][0] ?? null;
    }

    /**
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        if ($fulfillmentOrder->external_order_key) {
            return null;
        } else {
            return $this->client->createOutbound($externalOrder)->getData();
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
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        return $this->client->cancelOutbound(['consignment_no' => $fulfillmentOrder->external_order_key])->success;
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
        $externalOrders = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $externalOrders[$externalOrderKey] = $this->client->getOutboundList(['consignment_no' => $externalOrderKeys])->data['data'];
        }
        return $externalOrders;
    }

    #endregion

    #region Warehouse Stock Pull

    /**
     * @param array $condition
     * @return array
     * @throws \lujie\extend\authclient\JsonRpcException
     * @inheritdoc
     */
    protected function getExternalWarehouses(array $condition = []): array
    {
        return $this->client->getWarehouseList($condition)->getData();
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
        $data = $this->client->getInventory(['lstsku' => $externalItemKeys])->getData();
        return $data['data'];
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

}
