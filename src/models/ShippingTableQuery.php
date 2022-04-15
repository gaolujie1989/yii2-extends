<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ShippingTable]].
 *
 * @method ShippingTableQuery id($id)
 * @method ShippingTableQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ShippingTableQuery shippingTableId($shippingTableId)
 * @method ShippingTableQuery ownerId($ownerId)
 * @method ShippingTableQuery carrier($carrier);
 * @method ShippingTableQuery departure($departure);
 * @method ShippingTableQuery destination($destination);
 * @method ShippingTableQuery zone($zone);
 *
 * @method ShippingTableQuery orderByPrice($order = SORT_ASC)
 *
 * @method array|ShippingTable[] all($db = null)
 * @method array|ShippingTable|null one($db = null)
 * @method array|ShippingTable[] each($batchSize = 100, $db = null)
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
                    'shippingTableId' => 'shipping_table_id',
                    'ownerId' => 'owner_id',
                    'carrier' => 'carrier',
                    'departure' => 'departure',
                    'destination' => 'destination',
                    'zone' => 'zone',
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
     * @param array $carrierZones
     * @return $this
     * @inheritdoc
     */
    public function carrierZones(array $carrierZones): self
    {
        if (empty($carrierZones)) {
            return $this->andWhere('1=2');
        }
        $condition = ['OR'];
        foreach ($carrierZones as $carrier => $zone) {
            $condition[] = ['carrier' => $carrier, 'zone' => $zone];
        }
        return $this->andWhere($condition);
    }

    /**
     * @param array $carriers
     * @param int $defaultPrice
     * @return array
     * @inheritdoc
     */
    public function getShippingPrices(array $carriers = [], int $defaultPrice = 99999): array
    {
        $carrierPrices = $this->select(['MIN(price_cent) AS price_cent', 'carrier'])
            ->groupBy(['carrier'])
            ->indexBy('carrier')
            ->column();
        if ($carriers && $defaultPrice) {
            $carrierPrices = array_merge(array_fill_keys($carriers, $defaultPrice), $carrierPrices);
        }
        return $carrierPrices;
    }

    #region Limit Condition

    /**
     * @param int $lengthMM
     * @param int $widthMM
     * @param int $heightMM
     * @return $this
     * @inheritdoc
     */
    public function sizeMMLimit(int $lengthMM, int $widthMM, int $heightMM): self
    {
        $l2whMM = $lengthMM + 2 * ($widthMM + $heightMM);
        $lwhMM = $lengthMM + $widthMM + $heightMM;
        $lhMM = $lengthMM + $heightMM;
        $volumeMM3 = $lengthMM * $widthMM * $heightMM;
        return $this->limitCondition('length_mm_limit', $lengthMM)
            ->limitCondition('width_mm_limit', $widthMM)
            ->limitCondition('height_mm_limit', $heightMM)
            ->limitCondition('l2wh_mm_limit', $l2whMM)
            ->limitCondition('lwh_mm_limit', $lwhMM)
            ->limitCondition('lh_mm_limit', $lhMM)
            ->limitCondition('volume_mm3_limit', $volumeMM3)
            ->minLimitCondition('length_mm_min_limit', $lengthMM)
            ->minLimitCondition('width_mm_min_limit', $widthMM)
            ->minLimitCondition('height_mm_min_limit', $heightMM);
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
     * @param string $limitType
     * @param int $limitValue
     * @return $this
     * @inheritdoc
     */
    protected function limitCondition(string $limitType, int $limitValue): self
    {
        return $this->andWhere(['OR', [$limitType => 0], ['>', $limitType, $limitValue]]);
    }

    /**
     * @param string $limitType
     * @param int $limitValue
     * @return $this
     * @inheritdoc
     */
    protected function minLimitCondition(string $limitType, int $limitValue): self
    {
        return $this->andWhere(['OR', [$limitType => 0], ['<', $limitType, $limitValue]]);
    }

    /**
     * @param int $lengthMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function lengthMMLimit(int $lengthMM): self
    {
        return $this->limitCondition('length_mm_limit', $lengthMM);
    }

    /**
     * @param int $widthMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function widthMMLimit(int $widthMM): self
    {
        return $this->limitCondition('width_mm_limit', $widthMM);
    }

    /**
     * @param int $heightMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function heightMMLimit(int $heightMM): self
    {
        return $this->limitCondition('height_mm_limit', $heightMM);
    }

    /**
     * @param int $l2whMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function l2whMMLimit(int $l2whMM): self
    {
        return $this->limitCondition('l2wh_mm_limit', $l2whMM);
    }

    /**
     * @param int $lwhMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function lwhMMLimit(int $lwhMM): self
    {
        return $this->limitCondition('lwh_mm_limit', $lwhMM);
    }

    /**
     * @param int $lhMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function lhMMLimit(int $lhMM): self
    {
        return $this->limitCondition('lh_mm_limit', $lhMM);
    }

    /**
     * @param int $volumeMM3
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function volumeMM3Limit(int $volumeMM3): self
    {
        return $this->limitCondition('volume_mm3_limit', $volumeMM3);
    }

    /**
     * @param int $heightMM
     * @return $this
     * @deprecated
     * @inheritdoc
     */
    public function minHeightMMLimit(int $heightMM): self
    {
        return $this->minLimitCondition('height_mm_min_limit', $heightMM);
    }

    #endregion
}
