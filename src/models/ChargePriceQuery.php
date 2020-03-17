<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ChargePrice]].
 *
 * @method ChargePriceQuery id($id)
 * @method ChargePriceQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ChargePriceQuery chargePriceId($chargePriceId)
 * @method ChargePriceQuery chargeType($chargeType)
 * @method ChargePriceQuery customType($customType)
 * @method ChargePriceQuery modelType($modelType)
 * @method ChargePriceQuery modelId($modelId)
 * @method ChargePriceQuery parentModelId($parentModelId)
 * @method ChargePriceQuery priceTableId($priceTableId)
 * @method ChargePriceQuery status($status)
 * @method ChargePriceQuery ownerId($ownerId)
 *
 * @method array|ChargePrice[] all($db = null)
 * @method array|ChargePrice|null one($db = null)
 * @method array|ChargePrice[] each($batchSize = 100, $db = null)
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
                    'chargePriceId' => 'charge_price_id',
                    'chargeType' => 'charge_type',
                    'customType' => 'custom_type',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'parentModelId' => 'parent_model_id',
                    'priceTableId' => 'price_table_id',
                    'status' => 'status',
                    'ownerId' => 'owner_id',
                ],
            ]
        ]);
    }
}
