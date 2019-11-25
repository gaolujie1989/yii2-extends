<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ChargeTable]].
 *
 * @method ChargeTableQuery ownerId(int $ownerId)
 * @method ChargeTableQuery chargeType(string $chargeType);
 * @method ChargeTableQuery customType(string $customType);
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
                    'ownerId' => 'owner_id',
                    'chargeType' => 'charge_type',
                    'customType' => 'custom_type',
                ],
            ]
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
     * @param int $value
     * @return $this
     * @inheritdoc
     */
    public function limitValue(int $value): self
    {
        return $this->andWhere(['OR',
            [
                'AND',
                ['<=', 'min_limit', $value],
                ['>=', 'max_limit', $value],
            ],
            [
                'AND',
                ['<', 'max_limit', $value],
                ['>', 'over_limit_price_cent', 0],
                ['>', 'per_limit', 0],
            ]
        ]);
    }
}
