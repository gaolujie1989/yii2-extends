<?php

namespace lujie\stock\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Stock]].
 *
 * @method StockQuery itemId($itemId)
 * @method StockQuery locationId($locationId)
 *
 * @method array|Stock[] all($db = null)
 * @method array|Stock|null one($db = null)
 *
 * @see Stock
 */
class StockQuery extends \yii\db\ActiveQuery
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
                ]
            ]
        ]);
    }
}
