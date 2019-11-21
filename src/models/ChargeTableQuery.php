<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ChargeTable]].
 *
 * @method ChargePriceQuery chargeType(string $chargeType);
 * @method ChargePriceQuery ownerId(int $ownerId)
 *
 * @method ChargeTable[]|array all($db = null)
 * @method ChargeTable|array|null one($db = null)
 *
 * @see ChargeTable
 */
class ChargeTableQuery extends \yii\db\ActiveQuery
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
                    'chargeType' => 'charge_type',
                    'ownerId' => 'owner_id',
                ],
            ]
        ]);
    }

    /**
     * @param int $time
     * @return ChargeTableQuery
     * @inheritdoc
     */
    public function activeAt(int $time): ChargeTableQuery
    {
        return $this->andWhere(['<=', 'started_at', $time])->andWhere(['>=', 'ended_at', $time]);
    }
}
