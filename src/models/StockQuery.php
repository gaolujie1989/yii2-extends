<?php

namespace lujie\stock\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Stock]].
 *
 * @method StockQuery id($id)
 * @method StockQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method StockQuery stockId($stockId)
 * @method StockQuery itemId($itemId)
 * @method StockQuery locationId($locationId)
 *
 * @method array|Stock[] all($db = null)
 * @method array|Stock|null one($db = null)
 * @method array|Stock[] each($batchSize = 100, $db = null)
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
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'stockId' => 'stock_id',
                    'itemId' => 'item_id',
                    'locationId' => 'location_id',
                ]
            ]
        ];
    }

}
