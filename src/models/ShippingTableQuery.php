<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ShippingTable]].
 *
 * @method ChargePriceQuery carrier(string $carrier);
 * @method ChargePriceQuery ownerId(int $ownerId)
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
                    'ownerId' => 'owner_id',
                ],
            ]
        ]);
    }

    /**
     * @param int $time
     * @return ShippingTableQuery
     * @inheritdoc
     */
    public function activeAt(int $time): ShippingTableQuery
    {
        return $this->andWhere(['<=', 'started_at', $time])->andWhere(['>=', 'ended_at', $time]);
    }
}
