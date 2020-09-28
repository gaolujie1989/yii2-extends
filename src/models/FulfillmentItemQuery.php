<?php

namespace lujie\fulfillment\models;

use Generator;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentItem]].
 *
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
 * @method FulfillmentItemQuery externalItemId($externalItemId)
 * @method FulfillmentItemQuery externalItemNo($externalItemNo)
 * @method FulfillmentItemQuery externalItemParentId($externalItemParentId)
 * @method FulfillmentItemQuery itemPushedStatus($itemPushedStatus)
 * @method FulfillmentItemQuery accountId($accountId)
 *
 * @method FulfillmentItemQuery notQueued()
 * @method FulfillmentItemQuery itemPushed()
 * @method FulfillmentItemQuery newUpdatedItems()
 * @method FulfillmentItemQuery orderByStockPulledAt($sort = SORT_ASC)
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
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fulfillmentItemId' => 'fulfillment_item_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'itemId' => 'item_id',
                    'externalItemId' => 'external_item_id',
                    'externalItemNo' => 'external_item_no',
                    'externalItemParentId' => 'external_item_parent_id',
                    'itemPushedStatus' => 'item_pushed_status',
                    'accountId' => 'fulfillment_account_id',
                ],
                'queryConditions' => [
                    'notQueued' => ['!=', 'item_pushed_status', ExecStatusConst::EXEC_STATUS_QUEUED],
                    'itemPushed' => ['>', 'item_pushed_at', 0],
                    'newUpdatedItems' => 'item_updated_at > item_pushed_at',
                ],
                'querySorts' => [
                    'orderByStockPulledAt' => ['stock_pulled_at']
                ]
            ],
        ]);
    }

    /**
     * @param int $batchSize
     * @return Generator
     * @inheritdoc
     */
    public function batchItemIdsIndexByExternalItemId(int $batchSize = 100): Generator
    {
        $batch = $this->select(['item_id', 'external_item_id'])->asArray()->batch($batchSize);
        foreach ($batch as $items) {
            yield ArrayHelper::map($items, 'external_item_id', 'item_id');
        }
    }
}
