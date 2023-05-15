<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\QueryHelper;
use lujie\sales\channel\constants\SalesChannelConst;

/**
 * This is the ActiveQuery class for [[SalesChannelOrder]].
 *
 * @method SalesChannelOrderQuery id($id)
 * @method SalesChannelOrderQuery orderById($sort = SORT_ASC)
 * @method SalesChannelOrderQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelOrderQuery salesChannelOrderId($salesChannelOrderId)
 * @method SalesChannelOrderQuery salesChannelAccountId($salesChannelAccountId)
 * @method SalesChannelOrderQuery salesChannelStatus($salesChannelStatus)
 * @method SalesChannelOrderQuery orderId($orderId)
 * @method SalesChannelOrderQuery orderStatus($orderStatus)
 * @method SalesChannelOrderQuery externalOrderKey($externalOrderKey, bool|string $like = false)
 * @method SalesChannelOrderQuery externalOrderStatus($externalOrderStatus)
 * @method SalesChannelOrderQuery orderPushedStatus($orderPushedStatus)
 *
 * @method SalesChannelOrderQuery orderUpdatedAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery externalCreatedAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery externalUpdatedAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery orderPushedAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery orderPulledAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery createdAtBetween($from, $to = null)
 * @method SalesChannelOrderQuery updatedAtBetween($from, $to = null)
 *
 * @method SalesChannelOrderQuery needPull()
 * @method SalesChannelOrderQuery needPush()
 *
 * @method SalesChannelOrderQuery orderBySalesChannelOrderId($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderBySalesChannelAccountId($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByOrderId($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByOrderUpdatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByOrderPushedAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByOrderPulledAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByCreatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method SalesChannelOrderQuery indexBySalesChannelOrderId()
 * @method SalesChannelOrderQuery indexBySalesChannelAccountId()
 * @method SalesChannelOrderQuery indexByOrderId()
 * @method SalesChannelOrderQuery indexByExternalOrderKey()
 *
 * @method array getSalesChannelOrderIds()
 * @method array getSalesChannelAccountIds()
 * @method array getOrderIds()
 * @method array getExternalOrderKeys()
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
                    'externalOrderKey' => 'external_order_key',
                    'externalOrderStatus' => 'external_order_status',
                    'orderPushedStatus' => 'order_pushed_status',
                    'orderUpdatedAtBetween' => ['order_updated_at' => 'BETWEEN'],
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'orderPushedAtBetween' => ['order_pushed_at' => 'BETWEEN'],
                    'orderPulledAtBetween' => ['order_pulled_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'needPull' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
                            SalesChannelConst::CHANNEL_STATUS_PAID,
                            SalesChannelConst::CHANNEL_STATUS_PENDING,
                        ]
                    ],
                    'needPush' => [
                        'sales_channel_status' => [
                            SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED,
                            SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED,
                        ]
                    ],
                ],
                'querySorts' => [
                    'orderBySalesChannelOrderId' => 'sales_channel_order_id',
                    'orderBySalesChannelAccountId' => 'sales_channel_account_id',
                    'orderByOrderId' => 'order_id',
                    'orderByOrderUpdatedAt' => 'order_updated_at',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByOrderPushedAt' => 'order_pushed_at',
                    'orderByOrderPulledAt' => 'order_pulled_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexBySalesChannelOrderId' => 'sales_channel_order_id',
                    'indexBySalesChannelAccountId' => 'sales_channel_account_id',
                    'indexByOrderId' => 'order_id',
                    'indexByExternalOrderKey' => 'external_order_key',
                ],
                'queryReturns' => [
                    'getSalesChannelOrderIds' => ['sales_channel_order_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getSalesChannelAccountIds' => ['sales_channel_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getOrderIds' => ['order_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalOrderKeys' => ['external_order_key', FieldQueryBehavior::RETURN_COLUMN],
                    'maxExternalCreatedAt' => ['external_created_at', FieldQueryBehavior::RETURN_MAX],
                ]
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
