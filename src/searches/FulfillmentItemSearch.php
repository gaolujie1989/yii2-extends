<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentAccountQuery;
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
            [['fulfillment_account_id', 'item_id', 'external_item_id', 'external_item_no'], 'safe']
        ];
    }

    /**
     * @return FulfillmentItemQuery
     * @inheritdoc
     */
    public function query(): FulfillmentItemQuery
    {
        return static::find()->with('fulfillmentAccount')
            ->andFilterWhere(['LIKE', 'external_item_no', $this->external_item_no])
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'item_id' => $this->item_id,
                'external_item_id' => $this->external_item_id
            ]);
    }

    /**
     * @return FulfillmentAccountQuery
     * @inheritdoc
     */
    public function getFulfillmentAccount(): FulfillmentAccountQuery
    {
        return parent::getFulfillmentAccount()->select(['fulfillment_account_id', 'name']);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'fulfillment_account_name' => 'fulfillmentAccount.name',
        ]);
    }
}
