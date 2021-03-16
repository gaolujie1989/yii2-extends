<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;

use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\charging\models\ChargeTable;
use lujie\charging\models\ChargeTableQuery;
use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ChargeTableSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableSearch extends ChargeTable
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
            [['activeAt'], 'date'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|ChargeTableQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
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
            'over_limit_price' => 'over_limit_price_cent',
            'discountPercent' => 'additional.discountPercent',
            'started_time' => 'started_at',
            'ended_time' => 'ended_at',
        ];
        $unitAlias = [
            'display_min_limit' => 'min_limit',
            'display_max_limit' => 'max_limit',
            'display_per_limit' => 'per_limit',
            'display_min_over_limit' => 'min_over_limit',
            'display_max_over_limit' => 'max_over_limit',
        ];
        $row = ModelHelper::prepareArray($row, static::class, $alias);
        foreach ($unitAlias as $to => $from) {
            $row[$to] = UnitAliasBehavior::convert($row[$from], $row['limit_unit'], $row['display_limit_unit']);
        }
        return $row;
    }
}
