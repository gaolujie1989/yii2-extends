<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use yii\db\ActiveQuery;

/**
 * Trait FulfillmentItemRelationTrait
 *
 * @property FulfillmentItem $fulfillmentItem
 *
 * @package lujie\fulfillment\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FulfillmentItemRelationTrait
{
    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getFulfillmentItem(): ActiveQuery
    {
        return $this->hasOne(FulfillmentItem::class, ['external_item_key' => 'external_item_key']);
    }
}