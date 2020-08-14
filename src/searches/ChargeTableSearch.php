<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;


use lujie\charging\models\ChargeTable;
use lujie\charging\models\ChargeTableQuery;

/**
 * Class ChargeTableSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableSearch extends ChargeTable
{
    public $active_at;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
          [['charge_group', 'charge_type', 'custom_type', 'owner_id'], 'safe'],
          [['active_at'], 'date'],
        ];
    }

    /**
     * @return ChargeTableQuery
     * @inheritdoc
     */
    public function query(): ChargeTableQuery
    {
        $query = static::find()->andFilterWhere([
            'charge_group' => $this->charge_group,
            'charge_type' => $this->charge_type,
            'custom_type' => $this->custom_type,
            'owner_id' => $this->owner_id,
        ]);
        if ($this->active_at) {
            $query->andFilterWhere(['<=', 'started_at', $this->active_at])
                ->andFilterWhere(['>=', 'ended_at', $this->active_at]);
        }
        return $query;
    }
}