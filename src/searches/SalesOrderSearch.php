<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center\searches;


use lujie\sales\order\center\models\SalesOrder;
use lujie\sales\order\center\models\SalesOrderQuery;

/**
 * Class SalesOrderSearch
 * @package lujie\sales\order\center\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesOrderSearch extends SalesOrder
{
    public $orderedAtFrom;
    public $orderedAtTo;
    public $paidAtFrom;
    public $paidAtTo;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['sales_account_id', 'sales_account_name',
                'customer_id', 'customer_email', 'customer_phone',
                'external_order_id', 'external_order_no',
                'platform', 'country', 'shipping_country', 'currency',
                'payment_method', 'payment_status', 'transaction_no',
                'shipping_method', 'shipping_status', 'shipping_numbers',
                'status',
            ], 'safe'],
            [['orderedAtFrom', 'orderedAtTo', 'paidAtFrom', 'paidAtTo'], 'safe'],
        ];
    }

    /**
     * @return SalesOrderQuery
     * @inheritdoc
     */
    public function query(): SalesOrderQuery
    {
        $query = static::find();
        $query->andFilterWhere([
            'sales_account_id' => $this->sales_account_id,
            'customer_id' => $this->customer_id,
            'external_order_id' => $this->external_order_id,
            'platform' => $this->platform,
            'country' => $this->country,
            'shipping_country' => $this->shipping_country,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'shipping_method' => $this->shipping_method,
            'shipping_status' => $this->shipping_status,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['LIKE', 'sales_account_name', $this->sales_account_name]);
        $query->andFilterWhere(['LIKE', 'customer_email', $this->customer_email]);
        $query->andFilterWhere(['LIKE', 'customer_phone', $this->customer_phone]);
        $query->andFilterWhere(['LIKE', 'external_order_no', $this->external_order_no]);
        $query->andFilterWhere(['LIKE', 'transaction_no', $this->transaction_no]);
        $query->andFilterWhere(['LIKE', 'shipping_numbers', $this->shipping_numbers]);

        $query->andFilterWhere(['>', 'ordered_at', $this->orderedAtFrom]);
        $query->andFilterWhere(['<', 'ordered_at', $this->orderedAtTo]);
        $query->andFilterWhere(['>', 'paid_at', $this->paidAtFrom]);
        $query->andFilterWhere(['<', 'paid_at', $this->paidAtTo]);
        return $query;
    }
}
