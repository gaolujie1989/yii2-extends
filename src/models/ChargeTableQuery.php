<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ChargeTable]].
 *
 * @method ChargeTableQuery id($id)
 * @method ChargeTableQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ChargeTableQuery chargeTableId($chargeTableId)
 * @method ChargeTableQuery chargeType($chargeType)
 * @method ChargeTableQuery customType($customType)
 * @method ChargeTableQuery otherType($otherType)
 * @method ChargeTableQuery ownerId($ownerId)
 *
 * @method ChargeTableQuery orderByPrice($order = SORT_ASC)
 *
 * @method array|ChargeTable[] all($db = null)
 * @method array|ChargeTable|null one($db = null)
 * @method array|ChargeTable[] each($batchSize = 100, $db = null)
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
                    'chargeTableId' => 'charge_table_id',
                    'chargeType' => 'charge_type',
                    'customType' => 'custom_type',
                    'otherType' => 'other_type',
                    'ownerId' => 'owner_id',
                ],
                'querySorts' => [
                    'orderByPrice' => ['price_cent']
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
                ['OR', ['min_over_limit' => 0], ['<=', 'min_over_limit', $value]],
                ['OR', ['max_over_limit' => 0], ['>=', 'max_over_limit', $value]]
            ]
        ]);
    }
}
