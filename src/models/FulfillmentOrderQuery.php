<?php

namespace lujie\fulfillment\models;

use Generator;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
use lujie\fulfillment\constants\FulfillmentConst;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentOrder]].
 *
 * @method FulfillmentOrderQuery id($id)
 * @method FulfillmentOrderQuery accountId($accountId)
 * @method FulfillmentOrderQuery orderId($orderId)
 * @method FulfillmentOrderQuery orderStatus($orderStatus)
 * @method FulfillmentOrderQuery externalOrderId($externalOrderId)
 * @method FulfillmentOrderQuery externalOrderNo($externalOrderNo)
 * @method FulfillmentOrderQuery externalOrderStatus($externalOrderStatus)
 * @method FulfillmentOrderQuery fulfillmentStatus($externalOrderStatus)
 * @method FulfillmentOrderQuery orderPushedStatus($orderPushedStatus)
 * @method FulfillmentOrderQuery externalUpdatedAtFrom($externalUpdatedAtFrom)
 * @method FulfillmentOrderQuery externalUpdatedAtTo($externalUpdatedAtTo)
 * @method FulfillmentOrderQuery orderPulledAtFrom($orderPulledAtFrom)
 * @method FulfillmentOrderQuery orderPulledAtTo($orderPulledAtTo)
 *
 * @method FulfillmentOrderQuery notQueued()
 * @method FulfillmentOrderQuery pending()
 * @method FulfillmentOrderQuery processing()
 * @method FulfillmentOrderQuery pickingCancelling()
 * @method FulfillmentOrderQuery shipped()
 * @method FulfillmentOrderQuery orderByOrderPulledAt()
 *
 * @method int maxExternalUpdatedAt()
 * @method array getExternalOrderIds()
 *
 * @method array|FulfillmentOrder[] all($db = null)
 * @method array|FulfillmentOrder|null one($db = null)
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
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'accountId' => 'fulfillment_account_id',
                    'orderId' => 'order_id',
                    'orderStatus' => 'order_status',
                    'externalOrderId' => 'external_order_id',
                    'externalOrderNo' => 'external_order_no',
                    'externalOrderStatus' => 'external_order_status',
                    'fulfillmentStatus' => 'fulfillment_status',
                    'orderPushedStatus' => 'order_pushed_status',
                    'externalUpdatedAtFrom' => ['order_pulled_at' => '>='],
                    'externalUpdatedAtTo' => ['order_pulled_at' => '<='],
                    'orderPulledAtFrom' => ['order_pulled_at' => '>='],
                    'orderPulledAtTo' => ['order_pulled_at' => '<='],
                ],
                'queryConditions' => [
                    'notQueued' => ['!=', 'order_pushed_status', ExecStatusConst::EXEC_STATUS_QUEUED],
                    'pending' => [
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_PENDING,
                        ]
                    ],
                    'processing' => [
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_PUSHED,
                            FulfillmentConst::FULFILLMENT_STATUS_PICKING,
                        ]
                    ],
                    'pickingCancelling' => [
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_PICKING_CANCELLING,
                        ]
                    ],
                    'shipped' => [
                        'fulfillment_status' => [
                            FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
                        ]
                    ],
                ],
                'querySorts' => [
                    'orderByOrderPulledAt' => ['order_pulled_at']
                ],
                'queryReturns' => [
                    'maxExternalUpdatedAt' => ['external_updated_at', FieldQueryBehavior::RETURN_MAX],
                    'getExternalOrderIds' => ['external_order_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ]);
    }
}
