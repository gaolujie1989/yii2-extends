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
            'oi.item_id' => $this->item_id,
            'oi.currency' => $this->currency,
        ]);
        $query->andFilterWhere(['LIKE', 'oi.item_no', $this->item_no]);
        $query->andFilterWhere(['LIKE', 'oi.external_item_no', $this->external_item_no]);

        $query->innerJoinWith('order o');
        $query->andFilterWhere([
            'o.sales_account_id' => $this->salesAccountId,
            'o.platform' => $this->platform,
            'o.country' => $this->country,
        ]);
        $query->andFilterWhere(['>', 'o.ordered_at', $this->orderedAtFrom]);
        $query->andFilterWhere(['<', 'o.ordered_at', $this->orderedAtTo]);
        $query->andFilterWhere(['>', 'o.paid_at', $this->paidAtFrom]);
        $query->andFilterWhere(['<', 'o.paid_at', $this->paidAtTo]);

        return $query;
    }
}
