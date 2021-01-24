<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\searches;

use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\models\SalesChannelOrderQuery;

/**
 * Class SalesChannelOrderSearch
 * @package lujie\sales\channel\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderSearch extends SalesChannelOrder
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['sales_channel_account_id', 'sales_channel_status',
                'order_id', 'order_status',
                'external_order_key', 'external_order_status',
                'order_pushed_status'], 'safe'],
        ];
    }

    /**
     * @return SalesChannelOrderQuery
     * @inheritdoc
     */
    public function query(): SalesChannelOrderQuery
    {
        return static::find()->andFilterWhere([
            'sales_channel_account_id' => $this->sales_channel_account_id,
            'sales_channel_status' => $this->sales_channel_status,
            'order_id' => $this->order_id,
            'order_status' => $this->order_status,
            'external_order_key' => $this->external_order_key,
            'external_order_status' => $this->external_order_status,
            'order_pushed_status' => $this->order_pushed_status,
        ]);
    }
}