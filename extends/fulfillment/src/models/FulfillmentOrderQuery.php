<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\helpers\QueryHelper;
use lujie\fulfillment\constants\FulfillmentConst;

/**
 * This is the ActiveQuery class for [[FulfillmentOrder]].
 *
 * @method FulfillmentOrderQuery id($id)
 * @method FulfillmentOrderQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentOrderQuery fulfillmentOrderId($fulfillmentOrderId)
 * @method FulfillmentOrderQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentOrderQuery fulfillmentStatus($fulfillmentStatus)
 * @method FulfillmentOrderQuery fulfillmentType($fulfillmentType)
 * @method FulfillmentOrderQuery orderId($orderId)
 * @method FulfillmentOrderQuery orderStatus($orderStatus)
 * @method FulfillmentOrderQuery warehouseId($warehouseId)
 * @method FulfillmentOrderQuery externalOrderKey($externalOrderKey, bool|string $like = false)
 * @method FulfillmentOrderQuery externalOrderStatus($externalOrderStatus)
 * @method FulfillmentOrderQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 * @method FulfillmentOrderQuery orderPushedStatus($orderPushedStatus)
 *
 * @method FulfillmentOrderQuery orderUpdatedAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery externalCreatedAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery externalUpdatedAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery orderPushedAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery orderPulledAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery chargePulledAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery createdAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery updatedAtBetween($from, $to = null)
 * @method FulfillmentOrderQuery externalUpdatedAtFrom($externalUpdatedAtFrom)
 * @method FulfillmentOrderQuery externalUpdatedAtTo($externalUpdatedAtTo)
 * @method FulfillmentOrderQuery orderPulledAtFrom($orderPulledAtFrom)
 * @method FulfillmentOrderQuery orderPulledAtTo($orderPulledAtTo)
 *
 * @method FulfillmentOrderQuery inboundFulfillment()
 * @method FulfillmentOrderQuery shippingFulfillment()
 *
 * @method FulfillmentOrderQuery inboundFulfillmentPending()
 * @method FulfillmentOrderQuery inboundFulfillmentProcessing()
 * @method FulfillmentOrderQuery inboundFulfillmentInbounded()
 * @method FulfillmentOrderQuery inboundFulfillmentToCancelling()
 *
 * @method FulfillmentOrderQuery shippingFulfillmentPending()
 * @method FulfillmentOrderQuery shippingFulfillmentProcessing()
 * @method FulfillmentOrderQuery shippingFulfillmentShipped()
 * @method FulfillmentOrderQuery shippingFulfillmentToHolding()
 * @method FulfillmentOrderQuery shippingFulfillmentToShipping()
 * @method FulfillmentOrderQuery shippingFulfillmentToCancelling()
 *
 * @method FulfillmentOrderQuery chargeNotPulled()
 *
 * @method FulfillmentOrderQuery orderByFulfillmentOrderId($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByOrderId($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByOrderUpdatedAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByWarehouseId($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByOrderPushedAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByOrderPulledAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByChargePulledAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentOrderQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentOrderQuery indexByFulfillmentOrderId()
 * @method FulfillmentOrderQuery indexByFulfillmentAccountId()
 * @method FulfillmentOrderQuery indexByOrderId()
 * @method FulfillmentOrderQuery indexByWarehouseId()
 * @method FulfillmentOrderQuery indexByExternalOrderKey()
 * @method FulfillmentOrderQuery indexByExternalWarehouseKey()
 *
 * @method array getFulfillmentOrderIds()
 * @method array getFulfillmentAccountIds()
 * @method array getOrderIds()
 * @method array getWarehouseIds()
 * @method array getExternalOrderKeys()
 * @method array getExternalWarehouseKeys()
 * @method int maxExternalUpdatedAt()
 *
 * @method array|FulfillmentOrder[] all($db = null)
 * @method array|FulfillmentOrder|null one($db = null)
 * @method array|FulfillmentOrder[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentOrder
 */
class FulfillmentOrderQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fulfillmentOrderId' => 'fulfillment_order_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'fulfillmentStatus' => 'fulfillment_status',
                    'fulfillmentType' => 'fulfillment_type',
                    'orderId' => 'order_id',
                    'orderStatus' => 'order_status',
                    'warehouseId' => 'warehouse_id',
                    'externalOrderKey' => ['external_order_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalOrderStatus' => 'external_order_status',
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'orderPushedStatus' => 'order_pushed_status',
                    'orderUpdatedAtBetween' => ['order_updated_at' => 'BETWEEN'],
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'orderPushedAtBetween' => ['order_pushed_at' => 'BETWEEN'],
                    'orderPulledAtBetween' => ['order_pulled_at' => 'BETWEEN'],
                    'chargePulledAtBetween' => ['charge_pulled_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                    'externalUpdatedAtFrom' => ['external_updated_at' => '>='],
                    'externalUpdatedAtTo' => ['external_updated_at' => '<='],
                    'orderPulledAtFrom' => ['order_pulled_at' => '>='],
                    'orderPulledAtTo' => ['order_pulled_at' => '<='],
                ],
                'queryConditions' => [
                    'inboundFulfillment' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_INBOUND],
                    ],
                    'shippingFulfillment' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                    ],
                    #region Inbound Fulfillment Status
                    'inboundFulfillmentPending' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_INBOUND],
                        'fulfillment_status' => [
                            FulfillmentConst::INBOUND_STATUS_PENDING,
                        ]
                    ],
                    'inboundFulfillmentProcessing' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_INBOUND],
                        'fulfillment_status' => [
                            FulfillmentConst::INBOUND_STATUS_PROCESSING,
                            FulfillmentConst::INBOUND_STATUS_SHIPPED,
                            FulfillmentConst::INBOUND_STATUS_ARRIVED,
                            FulfillmentConst::INBOUND_STATUS_RECEIVED,
                        ]
                    ],
                    'inboundFulfillmentInbounded' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_INBOUND],
                        'fulfillment_status' => [
                            FulfillmentConst::INBOUND_STATUS_INBOUNDED,
                        ]
                    ],
                    'inboundFulfillmentToCancelling' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_INBOUND],
                        'fulfillment_status' => [
                            FulfillmentConst::INBOUND_STATUS_TO_CANCELLING,
                        ]
                    ],
                    #endregion
                    //#region Shipping Fulfillment Status
                    'shippingFulfillmentPending' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_PENDING,
                        ]
                    ],
                    'shippingFulfillmentProcessing' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
                            FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
                            FulfillmentConst::FULFILLMENT_STATUS_PICKING,
                            FulfillmentConst::FULFILLMENT_STATUS_SHIP_ERROR,
                        ]
                    ],
                    'shippingFulfillmentShipped' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
                        ]
                    ],
                    'shippingFulfillmentToHolding' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING,
                        ]
                    ],
                    'shippingFulfillmentToShipping' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING,
                        ]
                    ],
                    'shippingFulfillmentToCancelling' => [
                        'fulfillment_type' => [FulfillmentConst::FULFILLMENT_TYPE_SHIPPING],
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING,
                        ]
                    ],
                    #endregion
                    'chargeNotPulled' => ['charge_pulled_at' => 0]
                ],
                'querySorts' => [
                    'orderByFulfillmentOrderId' => 'fulfillment_order_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByOrderId' => 'order_id',
                    'orderByOrderUpdatedAt' => 'order_updated_at',
                    'orderByWarehouseId' => 'warehouse_id',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByOrderPushedAt' => 'order_pushed_at',
                    'orderByOrderPulledAt' => 'order_pulled_at',
                    'orderByChargePulledAt' => 'charge_pulled_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentOrderId' => 'fulfillment_order_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByOrderId' => 'order_id',
                    'indexByWarehouseId' => 'warehouse_id',
                    'indexByExternalOrderKey' => 'external_order_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentOrderIds' => ['fulfillment_order_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getOrderIds' => ['order_id', FieldQueryBehavior::RETURN_COLUMN, 'external_order_key'],
                    'getWarehouseIds' => ['warehouse_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalOrderKeys' => ['external_order_key', FieldQueryBehavior::RETURN_COLUMN, 'order_id'],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                    'maxExternalUpdatedAt' => ['external_updated_at', FieldQueryBehavior::RETURN_MAX],
                ],
            ]
        ];
    }

    /**
     * @param int $queuedDuration
     * @return $this
     * @inheritdoc
     */
    public function notQueuedOrQueuedButNotExecuted(int $queuedDuration = 3600): self
    {
        QueryHelper::notQueuedOrQueuedButNotExecuted($this, 'order_pushed_status', $queuedDuration);
        return $this;
    }
}
