<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentItemQuery;

/**
 * Class FulfillmentItemSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentItemSearch extends FulfillmentItem
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'item_id', 'external_item_key'], 'safe']
        ];
    }

    /**
     * @return FulfillmentItemQuery
     * @inheritdoc
     */
    public function query(): FulfillmentItemQuery
    {
        return static::find()
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'item_id' => $this->item_id,
                'external_item_key' => $this->external_item_key
            ]);
    }
}
