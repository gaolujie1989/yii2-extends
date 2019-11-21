<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[ChargePrice]].
 *
 * @method ChargePriceQuery chargeType(string $chargeType);
 * @method ChargePriceQuery modelType(string $modelType);
 * @method ChargePriceQuery modelId(int $modelId);
 * @method ChargePriceQuery parentModelId(int $parentModelId);
 * @method ChargePriceQuery ownerId(int $ownerId)
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
                ],
            ]
        ]);
    }
}
