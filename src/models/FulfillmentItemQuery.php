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
 * @method FulfillmentItemQuery externalItemKey($externalItemKey)
 * @method FulfillmentItemQuery itemPushedStatus($itemPushedStatus)
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
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fulfillmentItemId' => 'fulfillment_item_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'itemId' => 'item_id',
                    'externalItemKey' => 'external_item_key',
                    'itemPushedStatus' => 'item_pushed_status',
                ],
                'queryConditions' => [
                    'notQueued' => ['!=', 'item_pushed_status', ExecStatusConst::EXEC_STATUS_QUEUED],
                    'itemPushed' => ['>', 'item_pushed_at', 0],
                    'newUpdatedItems' => 'item_updated_at > item_pushed_at',
                ],
                'querySorts' => [
                    'orderByStockPulledAt' => ['stock_pulled_at']
                ]
            ]
        ];
    }

    /**
     * @return $this
     * @inheritdoc
     */
    public function queuedButNotExecuted($queuedDuration = 3600): self
    {
        return $this->andWhere(['AND',
            ['item_pushed_status' => ExecStatusConst::EXEC_STATUS_QUEUED],
            ['<=', 'item_pushed_at', time() - $queuedDuration],
        ]);
    }

}
