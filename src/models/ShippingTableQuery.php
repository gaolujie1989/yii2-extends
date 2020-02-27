<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ShippingTable]].
 *
 * @method ShippingTableQuery carrier($carrier);
 * @method ShippingTableQuery departure($departure);
 * @method ShippingTableQuery destination($destination);
 * @method ShippingTableQuery ownerId($ownerId)
 *
 * @method ShippingTableQuery orderByPrice($order = SORT_ASC)
 *
 * @method ShippingTable[]|array all($db = null)
 * @method ShippingTable|array|null one($db = null)
 *
 * @see ShippingTable
 */
class ShippingTableQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'carrier' => 'carrier',
                    'departure' => ['departure'],
                    'destination' => ['destination'],
                    'ownerId' => 'owner_id',
                ],
                'querySorts' => [
                    'orderByPrice' => ['price_cent']
                ],
            ],
        ]);
    }

    /**
     * @param int $time
     * @return $this
     * @inheritdoc
     */
    public function activeAt(int $time): self
    {
        return $this->andWhere(['<=', 'started_at', $time])->andWhere(['>=', 'ended_at', $time]);
    }

    /**
     * @param string $limitType
     * @param int $limitValue
     * @return $this
     * @inheritdoc
     */
    protected function limitCondition(string $limitType, int $limitValue): self
    {
        return $this->andWhere(['OR', [$limitType => 0], ['>=', $limitType, $limitValue]]);
    }

    /**
     * @param int $weightG
     * @return $this
     * @inheritdoc
     */
    public function weightGLimit(int $weightG): self
    {
        return $this->limitCondition('weight_g_limit', $weightG);
    }

    /**
     * @param int $lengthMM
     * @return $this
     * @inheritdoc
     */
    public function lengthMMLimit(int $lengthMM): self
    {
        return $this->limitCondition('length_mm_limit', $lengthMM);
    }

    /**
     * @param int $widthMM
     * @return $this
     * @inheritdoc
     */
    public function widthMMLimit(int $widthMM): self
    {
        return $this->limitCondition('width_mm_limit', $widthMM);
    }

    /**
     * @param int $heightMM
     * @return $this
     * @inheritdoc
     */
    public function heightMMLimit(int $heightMM): self
    {
        return $this->limitCondition('height_mm_limit', $heightMM);
    }

    /**
     * @param int $l2whMM
     * @return $this
     * @inheritdoc
     */
    public function l2whMMLimit(int $l2whMM): self
    {
        return $this->limitCondition('l2wh_mm_limit', $l2whMM);
    }

    /**
     * @param int $lhMM
     * @return $this
     * @inheritdoc
     */
    public function lhMMLimit(int $lhMM): self
    {
        return $this->limitCondition('lh_mm_limit', $lhMM);
    }
}
