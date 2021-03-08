<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;


use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingTableQuery;
use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ShippingTableSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableSearch extends ShippingTable
{
    use SearchTrait;

    /**
     * @var string
     */
    public $activeAt;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this), [
            [['carrier', 'departure', 'destination'], 'safe'],
            [['activeAt'], 'date'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|ShippingTableQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
        QueryHelper::filterValue($query, $this->getAttributes(['carrier', 'departure', 'destination']));
        if ($this->activeAt) {
            $query->andFilterWhere(['<=', 'started_at', $this->activeAt])
                ->andFilterWhere(['>=', 'ended_at', $this->activeAt]);
        }
        return $query;
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $alias = [
            'price' => 'price_cent',
            'started_time' => 'started_at',
            'ended_time' => 'ended_at',
        ];
        $sizeUnitAlias = [
            'length_cm_limit' => 'length_mm_limit',
            'width_cm_limit' => 'width_mm_limit',
            'height_cm_limit' => 'height_mm_limit',
            'height_cm_min_limit' => 'height_mm_min_limit',
            'l2wh_cm_limit' => 'l2wh_mm_limit',
            'lwh_cm_limit' => 'lwh_mm_limit',
            'lh_cm_limit' => 'lh_mm_limit',
        ];
        $row = ModelHelper::prepareArray($row, static::class, $alias);
        foreach ($sizeUnitAlias as $to => $from) {
            $row[$to] = UnitAliasBehavior::convert($row[$from], 'mm', 'cm');
        }
        $row['weight_kg_limit'] = UnitAliasBehavior::convert($row['weight_g_limit'], 'g', 'kg');
        $row['volume_l_limit'] = UnitAliasBehavior::convert($row['volume_mm3_limit'], 'mm3', 'l');
        return $row;
    }
}
