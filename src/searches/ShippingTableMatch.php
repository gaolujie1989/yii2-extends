<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\searches;

use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingTableQuery;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\models\Item;
use yii\db\ActiveQueryInterface;
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

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['item_key'], 'validateItem'],
            [['active_at'], 'date'],
            [['length_mm_limit', 'width_mm_limit', 'height_mm_limit'], 'required'],
            [['length_cm_limit', 'width_cm_limit', 'height_cm_limit'], 'required'],
        ];
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
                $this->length_mm_limit = $item->lengthMM;
                $this->width_mm_limit = $item->widthMM;
                $this->height_mm_limit = $item->heightMM;
            }
        }
    }

    /**
     * @return ActiveQueryInterface|ShippingTableQuery
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
        $groupByColumns = ['carrier', 'departure', 'destination'];
        $shippingPrices = (clone $query)
            ->select(['MIN(price_cent) AS price_cent'])
            ->addSelect($groupByColumns)
            ->groupBy($groupByColumns)
            ->asArray()
            ->all();
        $condition = array_merge(['OR'], $shippingPrices);
        $shippingTables = $query->andWhere($condition)->asArray()->all();

        $this->shippingTables = ArrayHelper::index($shippingTables, 'carrier', ['destination', 'departure']);
        $this->departureCarriers = ArrayHelper::map($shippingTables, 'departure', 'carrier');

        return true;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $safeAttributes = $this->safeAttributes();
        return array_merge(array_combine($safeAttributes, [
            'shippingTables' => 'shippingTables',
            'departureCarriers' => 'departureCarriers',
        ]));
    }
}
