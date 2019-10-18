<?php

namespace lujie\stock\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[StockMovement]].
 *
 * @method StockMovementQuery id($id)
 * @method StockMovementQuery itemId($itemId)
 * @method StockMovementQuery locationId($locationId)
 * @method StockMovementQuery reason($reason)
 *
 * @method int orderByMovementId($order = SORT_ASC)
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
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'itemId' => 'item_id',
                    'locationId' => 'location_id',
                    'reason' => 'reason',
                ],
                'querySorts' => [
                    'orderByMovementId' => 'stock_movement_id'
                ],
                'queryReturns' => [
                    'getTotalMovedQty' => ['moved_qty', FieldQueryBehavior::RETURN_SUM]
                ]
            ]
        ]);
    }
}
