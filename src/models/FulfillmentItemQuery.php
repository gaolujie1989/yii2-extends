<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;

/**
 * This is the ActiveQuery class for [[FulfillmentItem]].
 *
 * @method FulfillmentItemQuery id($id)
 * @method FulfillmentItemQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentItemQuery fulfillmentItemId($fulfillmentItemId)
 * @method FulfillmentItemQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentItemQuery itemId($itemId)
 * @method FulfillmentItemQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentItemQuery itemPushedStatus($itemPushedStatus)
 *
 * @method FulfillmentItemQuery itemUpdatedAtBetween($from, $to = null)
 * @method FulfillmentItemQuery externalCreatedAtBetween($from, $to = null)
 * @method FulfillmentItemQuery externalUpdatedAtBetween($from, $to = null)
 * @method FulfillmentItemQuery itemPushedAtBetween($from, $to = null)
 * @method FulfillmentItemQuery stockPulledAtBetween($from, $to = null)
 * @method FulfillmentItemQuery createdAtBetween($from, $to = null)
 * @method FulfillmentItemQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentItemQuery itemPushed()
 * @method FulfillmentItemQuery newUpdatedItems()
 *
 * @method FulfillmentItemQuery orderByFulfillmentItemId($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByItemId($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByItemUpdatedAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByItemPushedAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByStockPulledAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentItemQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentItemQuery indexByFulfillmentItemId()
 * @method FulfillmentItemQuery indexByFulfillmentAccountId()
 * @method FulfillmentItemQuery indexByItemId()
 * @method FulfillmentItemQuery indexByExternalItemKey()
 *
 * @method array getFulfillmentItemIds()
 * @method array getFulfillmentAccountIds()
 * @method array getItemIds()
 * @method array getExternalItemKeys()
 *
 * @method array|FulfillmentItem[] all($db = null)
 * @method array|FulfillmentItem|null one($db = null)
 * @method array|FulfillmentItem[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentItem
 */
class FulfillmentItemQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentItemId' => 'fulfillment_item_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'itemId' => 'item_id',
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'itemPushedStatus' => 'item_pushed_status',
                    'itemUpdatedAtBetween' => ['item_updated_at' => 'BETWEEN'],
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'itemPushedAtBetween' => ['item_pushed_at' => 'BETWEEN'],
                    'stockPulledAtBetween' => ['stock_pulled_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'itemPushed' => ['>', 'item_pushed_at', 0],
                    'newUpdatedItems' => 'item_updated_at > item_pushed_at',
                ],
                'querySorts' => [
                    'orderByFulfillmentItemId' => 'fulfillment_item_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByItemId' => 'item_id',
                    'orderByItemUpdatedAt' => 'item_updated_at',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByItemPushedAt' => 'item_pushed_at',
                    'orderByStockPulledAt' => 'stock_pulled_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentItemId' => 'fulfillment_item_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByItemId' => 'item_id',
                    'indexByExternalItemKey' => 'external_item_key',
                ],
                'queryReturns' => [
                    'getFulfillmentItemIds' => ['fulfillment_item_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getItemIds' => ['item_id', FieldQueryBehavior::RETURN_COLUMN, 'external_item_key'],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN, 'item_id'],
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
        return $this->andWhere(['OR',
            ['!=', 'item_pushed_status', ExecStatusConst::EXEC_STATUS_QUEUED],
            ['AND',
                ['item_pushed_status' => ExecStatusConst::EXEC_STATUS_QUEUED],
                ['<=', 'updated_at', time() - $queuedDuration],
            ]
        ]);
    }
}
