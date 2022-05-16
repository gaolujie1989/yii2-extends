<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentItemQuery;
use lujie\fulfillment\models\FulfillmentOrderQuery;
use lujie\sales\channel\constants\SalesChannelConst;

/**
 * This is the ActiveQuery class for [[SalesChannelOrder]].
 *
 * @method SalesChannelOrderQuery id($id)
 * @method SalesChannelOrderQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelOrderQuery salesChannelOrderId($salesChannelOrderId)
 * @method SalesChannelOrderQuery salesChannelAccountId($salesChannelAccountId)
 * @method SalesChannelOrderQuery salesChannelStatus($salesChannelStatus)
 * @method SalesChannelOrderQuery orderId($orderId)
 * @method SalesChannelOrderQuery orderStatus($orderStatus)
 * @method SalesChannelOrderQuery externalOrderKey($externalOrderKey)
 * @method SalesChannelOrderQuery externalOrderStatus($externalOrderStatus)
 * @method SalesChannelOrderQuery orderPushedStatus($orderPushedStatus)
 *
 * @method SalesChannelOrderQuery pending()
 * @method SalesChannelOrderQuery processing()
 * @method SalesChannelOrderQuery pendingOrProcessing()
 * @method SalesChannelOrderQuery shipped()
 * @method SalesChannelOrderQuery cancelled()
 * @method SalesChannelOrderQuery toShipped()
 * @method SalesChannelOrderQuery toCancelled()
 * @method SalesChannelOrderQuery orderByOrderPulledAt()
 *
 * @method int maxExternalCreatedAt()
 *
 * @method array|SalesChannelOrder[] all($db = null)
 * @method array|SalesChannelOrder|null one($db = null)
 * @method array|SalesChannelOrder[] each($batchSize = 100, $db = null)
 *
 * @see SalesChannelOrder
 */
class SalesChannelOrderQuery extends \yii\db\ActiveQuery
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
                    'salesChannelOrderId' => 'sales_channel_order_id',
                    'salesChannelAccountId' => 'sales_channel_account_id',
                    'salesChannelStatus' => 'sales_channel_status',
                    'orderId' => 'order_id',
                    'orderStatus' => 'order_status',
                    'externalOrderKey' => ['external_order_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalOrderStatus' => 'external_order_status',
                    'orderPushedStatus' => 'order_pushed_status',
                ],
                'queryConditions' => [
                    'pending' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
                        ]
                    ],
                    'processing' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_PAID,
                        ]
                    ],
                    'pendingOrProcessing' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
                            SalesChannelConst::CHANNEL_STATUS_PAID,
                        ]
                    ],
                    'shipped' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_SHIPPED,
                        ]
                    ],
                    'cancelled' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_CANCELLED,
                        ]
                    ],
                    'toShipped' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED,
                        ]
                    ],
                    'toCancelled' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED,
                        ]
                    ],
                ],
                'queryReturns' => [
                    'maxExternalCreatedAt' => ['external_created_at', FieldQueryBehavior::RETURN_MAX],
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
}
