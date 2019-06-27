<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center\searches;


use lujie\sales\order\center\models\SalesOrderItem;
use lujie\sales\order\center\models\SalesOrderItemQuery;

/**
 * Class SalesOrderItemSearch
 * @package lujie\sales\order\center\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesOrderItemSearch extends SalesOrderItem
{
    public $salesAccountId;
    public $platform;
    public $country;
    public $orderedAtFrom;
    public $orderedAtTo;
    public $paidAtFrom;
    public $paidAtTo;

    public function rules(): array
    {
        return [
            [['item_id', 'item_no', 'external_item_no', 'currency'], 'safe'],
        ];
    }

    /**
     * @return SalesOrderItemQuery
     * @inheritdoc
     */
    public function query(): SalesOrderItemQuery
    {
        $query = static::find()->alias('oi');
        $query->andFilterWhere([
            'soi.item_id' => $this->item_id,
            'soi.currency' => $this->currency,
        ]);
        $query->andFilterWhere(['LIKE', 'soi.item_no', $this->item_no]);
        $query->andFilterWhere(['LIKE', 'soi.external_item_no', $this->external_item_no]);

        $query->innerJoinWith('order o');
        $query->andFilterWhere([
            'so.sales_account_id' => $this->salesAccountId,
            'so.platform' => $this->platform,
            'so.country' => $this->country,
        ]);
        $query->andFilterWhere(['>', 'so.ordered_at', $this->orderedAtFrom]);
        $query->andFilterWhere(['<', 'so.ordered_at', $this->orderedAtTo]);
        $query->andFilterWhere(['>', 'so.paid_at', $this->paidAtFrom]);
        $query->andFilterWhere(['<', 'so.paid_at', $this->paidAtTo]);

        return $query;
    }
}
