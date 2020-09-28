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
    public $activeAt;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['charge_group', 'charge_type', 'custom_type', 'owner_id'], 'safe'],
            [['activeAt'], 'date'],
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
        if ($this->activeAt) {
            $query->andFilterWhere(['<=', 'started_at', $this->activeAt])
                ->andFilterWhere(['>=', 'ended_at', $this->activeAt]);
        }
        return $query;
    }
}