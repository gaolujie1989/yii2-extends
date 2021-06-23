<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
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
 * @method FulfillmentOrderQuery externalOrderKey($externalOrderKey)
 * @method FulfillmentOrderQuery externalOrderStatus($externalOrderStatus)
 * @method FulfillmentOrderQuery externalWarehouseKey($externalWarehouseKey)
 * @method FulfillmentOrderQuery orderPushedStatus($orderPushedStatus)
 *
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
 * @method FulfillmentOrderQuery orderByOrderPulledAt()
 *
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
                    'externalOrderKey' => 'external_order_key',
                    'externalOrderStatus' => 'external_order_status',
                    'externalWarehouseKey' => 'external_warehouse_key',
                    'orderPushedStatus' => 'order_pushed_status',

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
                    //Inbound Fulfillment Status
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
                    //Shipping Fulfillment Status
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
                            FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING,
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
                ],
                'queryReturns' => [
                    'maxExternalUpdatedAt' => ['external_updated_at', FieldQueryBehavior::RETURN_MAX]
                ],
                'querySorts' => [
                    'orderByOrderPulledAt' => ['order_pulled_at']
                ],
            ]
        ];
    }

    /**
     * @param int $queuedDuration
     * @return $this
     * @inheritdoc
     */
    public function notQueuedOrQueuedButNotExecuted($queuedDuration = 3600): self
    {
        return $this->andWhere(['OR',
            ['!=', 'order_pushed_status', ExecStatusConst::EXEC_STATUS_QUEUED],
            ['AND',
                ['order_pushed_status' => ExecStatusConst::EXEC_STATUS_QUEUED],
                ['<=', 'updated_at', time() - $queuedDuration],
            ]
        ]);
    }

    /**
     * @param bool $indexByOrderId
     * @return array
     * @inheritdoc
     */
    public function getExternalOrderKeys(bool $indexByOrderId = true): array
    {
        if ($indexByOrderId) {
            $this->indexBy('order_id');
        }
        return $this->select(['external_order_key'])->column();
    }
}
