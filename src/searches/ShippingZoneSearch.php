<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;

use lujie\alias\behaviors\AliasBehaviorTrait;
use lujie\charging\models\ShippingTableQuery;
use lujie\charging\models\ShippingZone;
use lujie\extend\db\SearchTrait;
use yii\db\ActiveQueryInterface;

/**
 * Class ShippingZoneSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneSearch extends ShippingZone
{
    use AliasBehaviorTrait, SearchTrait;

    /**
     * @var string
     */
    public $active_at;

    /**
     * @var string
     */
    public $postal_code;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['active_at'], 'date'],
            [['postal_code'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|ShippingTableQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        if ($this->active_at) {
            $query->andFilterWhere(['<=', 'started_at', $this->active_at])
                ->andFilterWhere(['>=', 'ended_at', $this->active_at]);
        }
        if ($this->postal_code) {
            $query->andFilterWhere(['<=', 'postal_code_from', $this->postal_code])
                ->andFilterWhere(['>=', 'postal_code_to', $this->postal_code]);
        }
        return $query;
    }
}
