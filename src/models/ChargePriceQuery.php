<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ChargePrice]].
 *
 * @method ChargePriceQuery chargeType($chargeType);
 * @method ChargePriceQuery modelType($modelType);
 * @method ChargePriceQuery modelId($modelId);
 * @method ChargePriceQuery parentModelId($parentModelId);
 * @method ChargePriceQuery ownerId($ownerId)
 * @method ChargePriceQuery status($status)
 *
 * @method ChargePrice[]|array all($db = null)
 * @method ChargePrice|array|null one($db = null)
 *
 * @see ChargePrice
 */
class ChargePriceQuery extends \yii\db\ActiveQuery
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
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'parentModelId' => 'parent_model_id',
                    'ownerId' => 'owner_id',
                    'status' => 'status',
                ],
            ]
        ]);
    }
}
