<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;

use lujie\charging\models\ShippingTable;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\QueryHelper;
use lujie\extend\models\Item;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ShippingTableSearch
 * @package lujie\charging\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableMatch extends ShippingTable
{
    public $active_at;

    public $item_key;

    public $postal_code;

    /**
     * @var DataLoaderInterface
     */
    public $itemLoader;

    /**
     * @var array
     */
    public $shippingTables;

    /**
     * @var array
     */
    public $departureCarriers;

    public $item;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['carrier', 'departure', 'destination', 'zone', 'currency', 'weight_kg_limit'], 'safe'],
            [['item_key'], 'validateItem'],
            [['active_at'], 'date'],
            [['length_cm_limit', 'width_cm_limit', 'height_cm_limit'], 'required', 'isEmpty' => static function($v) {
                return empty($v);
            }],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), $this->aliasBehaviors());
    }

    /**
     * @return Item|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function validateItem(): void
    {
        if ($this->item_key && $this->itemLoader) {
            $this->itemLoader = Instance::ensure($this->itemLoader, DataLoaderInterface::class);
            $item = $this->itemLoader->get($this->item_key);
            if ($item) {
                $this->item = $item;
                $this->length_mm_limit = $item->lengthMM;
                $this->width_mm_limit = $item->widthMM;
                $this->height_mm_limit = $item->heightMM;
            }
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function match(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $query = static::find()
            ->sizeMMLimit($this->length_mm_limit, $this->width_mm_limit, $this->height_mm_limit)
            ->weightGLimit($this->weight_g_limit ?: 1);
        if ($this->active_at) {
            $query->andFilterWhere(['<=', 'started_at', $this->active_at])
                ->andFilterWhere(['>=', 'ended_at', $this->active_at]);
        }
        QueryHelper::filterValue($query, $this->getAttributes(['carrier', 'departure', 'destination', 'zone', 'currency']));
        $groupByColumns = ['carrier', 'departure', 'destination'];
        $shippingPrices = (clone $query)
            ->select(['MIN(price_cent) AS price_cent'])
            ->addSelect($groupByColumns)
            ->groupBy($groupByColumns)
            ->asArray()
            ->all();
        $condition = array_merge(['OR'], $shippingPrices);
        $shippingTables = $query->andWhere($condition)
            ->orderBy([
                'departure' => SORT_ASC,
                'carrier' => SORT_ASC,
                'destination' => SORT_ASC,
            ])
            ->asArray()
            ->all();

        $shippingTables = ShippingTableSearch::prepareRows($shippingTables);
        $this->departureCarriers = ArrayHelper::map($shippingTables, 'carrier', 'carrier', 'departure');
        $shippingTables = ArrayHelper::index($shippingTables, 'carrier', ['destination', 'departure']);
        foreach ($shippingTables as $key => $shippingTable) {
            $shippingTables[$key]['destination'] = $key;
        }
        $this->shippingTables = array_values($shippingTables);
        return true;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $safeAttributes = $this->safeAttributes();
        return array_merge(array_combine($safeAttributes, $safeAttributes), [
            'item' => 'item',
            'shippingTables' => 'shippingTables',
            'departureCarriers' => 'departureCarriers',
        ]);
    }
}
