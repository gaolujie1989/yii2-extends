<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[SalesChannelItem]].
 *
 * @method SalesChannelItemQuery id($id)
 * @method SalesChannelItemQuery orderById($sort = SORT_ASC)
 * @method SalesChannelItemQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelItemQuery salesChannelItemId($salesChannelItemId)
 * @method SalesChannelItemQuery salesChannelAccountId($salesChannelAccountId)
 * @method SalesChannelItemQuery itemType($itemType)
 * @method SalesChannelItemQuery itemId($itemId)
 * @method SalesChannelItemQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method SalesChannelItemQuery externalItemNo($externalItemNo, bool|string $like = false)
 * @method SalesChannelItemQuery itemPushedStatus($itemPushedStatus)
 *
 * @method SalesChannelItemQuery itemUpdatedAtBetween($from, $to = null)
 * @method SalesChannelItemQuery externalCreatedAtBetween($from, $to = null)
 * @method SalesChannelItemQuery externalUpdatedAtBetween($from, $to = null)
 * @method SalesChannelItemQuery itemPushedAtBetween($from, $to = null)
 * @method SalesChannelItemQuery stockPushedAtBetween($from, $to = null)
 * @method SalesChannelItemQuery createdAtBetween($from, $to = null)
 * @method SalesChannelItemQuery updatedAtBetween($from, $to = null)
 *
 * @method SalesChannelItemQuery itemPushed()
 * @method SalesChannelItemQuery itemNotPushed()
 * @method SalesChannelItemQuery stockPushed()
 * @method SalesChannelItemQuery stockNotPushed()
 *
 * @method SalesChannelItemQuery orderBySalesChannelItemId($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderBySalesChannelAccountId($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByItemId($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByItemUpdatedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByItemPushedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByStockPushedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByCreatedAt($sort = SORT_ASC)
 * @method SalesChannelItemQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method SalesChannelItemQuery indexBySalesChannelItemId()
 * @method SalesChannelItemQuery indexBySalesChannelAccountId()
 * @method SalesChannelItemQuery indexByItemId()
 * @method SalesChannelItemQuery indexByExternalItemKey()
 * @method SalesChannelItemQuery indexByExternalItemNo()
 *
 * @method array getSalesChannelItemIds()
 * @method array getSalesChannelAccountIds()
 * @method array getItemIds()
 * @method array getExternalItemKeys()
 * @method array getExternalItemNos()
 *
 * @method array|SalesChannelItem[] all($db = null)
 * @method array|SalesChannelItem|null one($db = null)
 * @method array|SalesChannelItem[] each($batchSize = 100, $db = null)
 *
 * @see SalesChannelItem
 */
class SalesChannelItemQuery extends \yii\db\ActiveQuery
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
                    'salesChannelItemId' => 'sales_channel_item_id',
                    'salesChannelAccountId' => 'sales_channel_account_id',
                    'itemType' => 'item_type',
                    'itemId' => 'item_id',
                    'externalItemKey' => 'external_item_key',
                    'externalItemNo' => 'external_item_no',
                    'itemPushedStatus' => 'item_pushed_status',
                    'itemUpdatedAtBetween' => ['item_updated_at' => 'BETWEEN'],
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'itemPushedAtBetween' => ['item_pushed_at' => 'BETWEEN'],
                    'stockPushedAtBetween' => ['stock_pushed_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'itemPushed' => ['>', 'item_pushed_at', 0],
                    'itemNotPushed' => ['item_pushed_at' => 0],
                    'stockPushed' => ['>', 'stock_pushed_at', 0],
                    'stockNotPushed' => ['stock_pushed_at' => 0],
                ],
                'querySorts' => [
                    'orderBySalesChannelItemId' => 'sales_channel_item_id',
                    'orderBySalesChannelAccountId' => 'sales_channel_account_id',
                    'orderByItemId' => 'item_id',
                    'orderByItemUpdatedAt' => 'item_updated_at',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByItemPushedAt' => 'item_pushed_at',
                    'orderByStockPushedAt' => 'stock_pushed_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexBySalesChannelItemId' => 'sales_channel_item_id',
                    'indexBySalesChannelAccountId' => 'sales_channel_account_id',
                    'indexByItemId' => 'item_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalItemNo' => 'external_item_no',
                ],
                'queryReturns' => [
                    'getSalesChannelItemIds' => ['sales_channel_item_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getSalesChannelAccountIds' => ['sales_channel_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getItemIds' => ['item_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemNos' => ['external_item_no', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
