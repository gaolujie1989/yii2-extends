<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[SalesChannelOrderData]].
 *
 * @method SalesChannelOrderDataQuery id($id)
 * @method SalesChannelOrderDataQuery orderById($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelOrderDataQuery salesChannelOrderDataId($salesChannelOrderDataId)
 * @method SalesChannelOrderDataQuery salesChannelOrderId($salesChannelOrderId)
 *
 * @method SalesChannelOrderDataQuery createdAtBetween($from, $to = null)
 * @method SalesChannelOrderDataQuery updatedAtBetween($from, $to = null)
 *
 * @method SalesChannelOrderDataQuery orderBySalesChannelOrderDataId($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderBySalesChannelOrderId($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByCreatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method SalesChannelOrderDataQuery indexBySalesChannelOrderDataId()
 * @method SalesChannelOrderDataQuery indexBySalesChannelOrderId()
 *
 * @method array getSalesChannelOrderDataIds()
 * @method array getSalesChannelOrderIds()
 *
 * @method array|SalesChannelOrderData[] all($db = null)
 * @method array|SalesChannelOrderData|null one($db = null)
 * @method array|SalesChannelOrderData[] each($batchSize = 100, $db = null)
 *
 * @see SalesChannelOrderData
 */
class SalesChannelOrderDataQuery extends \yii\db\ActiveQuery
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
                    'salesChannelOrderDataId' => 'sales_channel_order_data_id',
                    'salesChannelOrderId' => 'sales_channel_order_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderBySalesChannelOrderDataId' => 'sales_channel_order_data_id',
                    'orderBySalesChannelOrderId' => 'sales_channel_order_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexBySalesChannelOrderDataId' => 'sales_channel_order_data_id',
                    'indexBySalesChannelOrderId' => 'sales_channel_order_id',
                ],
                'queryReturns' => [
                    'getSalesChannelOrderDataIds' => ['sales_channel_order_data_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getSalesChannelOrderIds' => ['sales_channel_order_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
