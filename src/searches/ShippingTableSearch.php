<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;


use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingTableQuery;

/**
 * Class ShippingTableSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableSearch extends ShippingTable
{
    public $active_at;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
          [['carrier', 'departure', 'destination', 'owner_id'], 'safe'],
          [['active_at'], 'date'],
        ];
    }

    /**
     * @return ShippingTableQuery
     * @inheritdoc
     */
    public function query(): ShippingTableQuery
    {
        $query = static::find()->andFilterWhere([
            'carrier' => $this->carrier,
            'departure' => $this->departure,
            'destination' => $this->destination,
            'owner_id' => $this->owner_id,
        ]);
        if ($this->active_at) {
            $query->andFilterWhere(['<=', 'started_at', $this->active_at])
                ->andFilterWhere(['>=', 'ended_at', $this->active_at]);
        }
        return $query;
    }
}