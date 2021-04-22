<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;

use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\charging\models\ShippingTableQuery;
use lujie\charging\models\ShippingZone;
use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ShippingZoneSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneSearch extends ShippingZone
{
    use SearchTrait;

    /**
     * @var string
     */
    public $activeAt;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['postalCode'], 'safe'],
            [['activeAt'], 'date'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|ShippingTableQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        if ($this->activeAt) {
            $query->andFilterWhere(['<=', 'started_at', $this->activeAt])
                ->andFilterWhere(['>=', 'ended_at', $this->activeAt]);
        }
        if ($this->postalCode) {
            $query->andFilterWhere(['<=', 'postal_code_from', $this->postalCode])
                ->andFilterWhere(['>=', 'postal_code_to', $this->postalCode]);
        }
        return $query;
    }
}
