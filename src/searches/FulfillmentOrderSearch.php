<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

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
        return static::find()
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
}
