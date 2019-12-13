<?php

namespace lujie\fulfillment\models;

use Generator;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentItem]].
 *
 * @method FulfillmentItemQuery id($id)
 * @method FulfillmentItemQuery accountId($accountId)
 * @method FulfillmentItemQuery itemId($itemId)
 * @method FulfillmentItemQuery externalItemId($externalItemId)
 * @method FulfillmentItemQuery externalItemNo($externalItemNo)
 *
 * @method FulfillmentItemQuery hasExternalItemId()
 * @method FulfillmentItemQuery newUpdatedItems()
 * @method FulfillmentItemQuery orderByStockPulledAt($sort = SORT_ASC)
 *
 * @method array|FulfillmentItem[] all($db = null)
 * @method array|FulfillmentItem|null one($db = null)
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
                    'accountId' => 'fulfillment_account_id',
                    'itemId' => 'item_id',
                    'externalItemId' => 'external_item_id',
                    'externalItemNo' => 'external_item_no',
                ],
                'queryConditions' => [
                    'hasExternalItemId' => ['>', 'external_item_id', 0],
                    'newUpdatedItems' => ['item_updated_at > item_pushed_at'],
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
