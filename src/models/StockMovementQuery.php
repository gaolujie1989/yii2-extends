<?php

namespace lujie\stock\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[StockMovement]].
 *
 * @method StockMovementQuery itemId($itemId)
 * @method StockMovementQuery locationId($locationId)
 * @method StockMovementQuery reason($reason)
 *
 * @method int getTotalMovedQty()
 *
 * @method array|StockMovement[] all($db = null)
 * @method array|StockMovement|null one($db = null)
 *
 * @see StockMovement
 */
class StockMovementQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'class' => FieldQueryBehavior::class,
            'queryFields' => [
                'itemId' => 'item_id',
                'locationId' => 'location_id',
                'reason' => 'reason',
            ],
            'queryReturn' => [
                'getTotalMovedQty' => ['move_qty', FieldQueryBehavior::RETURN_SUM]
            ]
        ]);
    }
}
