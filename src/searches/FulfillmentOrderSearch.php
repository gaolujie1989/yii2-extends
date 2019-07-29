<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentAccountQuery;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentOrderQuery;

/**
 * Class FulfillmentItemSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentOrderSearch extends FulfillmentOrder
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'order_id', 'external_order_id', 'external_order_no',
                'fulfillment_status', 'order_status', 'external_order_status'], 'safe']
        ];
    }

    /**
     * @return FulfillmentOrderQuery
     * @inheritdoc
     */
    public function query(): FulfillmentOrderQuery
    {
        return static::find()->with('fulfillmentAccount')
            ->andFilterWhere(['LIKE', 'external_order_no', $this->external_order_no])
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'order_id' => $this->order_id,
                'external_order_id' => $this->external_order_id,
                'fulfillment_status' => $this->fulfillment_status,
                'order_status' => $this->order_status,
                'external_order_status' => $this->external_order_status,
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
