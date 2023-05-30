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
 * @method SalesChannelOrderDataQuery salesChannelAccountId($salesChannelAccountId)
 * @method SalesChannelOrderDataQuery externalOrderKey($externalOrderKey, bool|string $like = false)
 * @method SalesChannelOrderDataQuery externalOrderNo($externalOrderNo, bool|string $like = false)
 *
 * @method SalesChannelOrderDataQuery externalCreatedAtBetween($from, $to = null)
 * @method SalesChannelOrderDataQuery externalUpdatedAtBetween($from, $to = null)
 * @method SalesChannelOrderDataQuery createdAtBetween($from, $to = null)
 * @method SalesChannelOrderDataQuery updatedAtBetween($from, $to = null)
 *
 * @method SalesChannelOrderDataQuery orderBySalesChannelOrderDataId($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderBySalesChannelAccountId($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByCreatedAt($sort = SORT_ASC)
 * @method SalesChannelOrderDataQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method SalesChannelOrderDataQuery indexBySalesChannelOrderDataId()
 * @method SalesChannelOrderDataQuery indexBySalesChannelAccountId()
 * @method SalesChannelOrderDataQuery indexByExternalOrderKey()
 * @method SalesChannelOrderDataQuery indexByExternalOrderNo()
 *
 * @method array getSalesChannelOrderDataIds()
 * @method array getSalesChannelAccountIds()
 * @method array getExternalOrderKeys()
 * @method array getExternalOrderNos()
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
                    'salesChannelAccountId' => 'sales_channel_account_id',
                    'externalOrderKey' => 'external_order_key',
                    'externalOrderNo' => 'external_order_no',
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderBySalesChannelOrderDataId' => 'sales_channel_order_data_id',
                    'orderBySalesChannelAccountId' => 'sales_channel_account_id',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexBySalesChannelOrderDataId' => 'sales_channel_order_data_id',
                    'indexBySalesChannelAccountId' => 'sales_channel_account_id',
                    'indexByExternalOrderKey' => 'external_order_key',
                    'indexByExternalOrderNo' => 'external_order_no',
                ],
                'queryReturns' => [
                    'getSalesChannelOrderDataIds' => ['sales_channel_order_data_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getSalesChannelAccountIds' => ['sales_channel_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalOrderKeys' => ['external_order_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalOrderNos' => ['external_order_no', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
