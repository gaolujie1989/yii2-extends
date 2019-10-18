<?php

namespace lujie\fulfillment\models;

use Generator;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
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
 *
 * @method FulfillmentOrderQuery processing()
 * @method FulfillmentOrderQuery orderByOrderPulledAt()
 *
 * @method int maxExternalUpdatedAt()
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
                ],
                'queryConditions' => [
                    'processing' => [
                        'fulfillment_status' => [
                            FulfillmentConst::ORDER_STATUS_PUSHED,
                            FulfillmentConst::ORDER_STATUS_PICKING,
                        ]
                    ]
                ],
                'querySorts' => [
                    'orderByOrderPulledAt' => ['order_pulled_at']
                ],
                'queryReturns' => [
                    'maxExternalUpdatedAt' => [['external_order_updated_at', FieldQueryBehavior::RETURN_MAX]]
                ]
            ]
        ]);
    }
}
