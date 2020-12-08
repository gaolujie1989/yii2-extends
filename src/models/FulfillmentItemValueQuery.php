<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentItemValue]].
 *
 * @method FulfillmentItemValueQuery id($id)
 * @method FulfillmentItemValueQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentItemValueQuery fulfillmentItemValueId($fulfillmentItemValueId)
 * @method FulfillmentItemValueQuery fulfillmentDailyStockMovementId($fulfillmentDailyStockMovementId)
 * @method FulfillmentItemValueQuery itemId($itemId)
 * @method FulfillmentItemValueQuery warehouseId($warehouseId)
 * @method FulfillmentItemValueQuery valueDate($valueDate)
 *
 * @method array|FulfillmentItemValue[] all($db = null)
 * @method array|FulfillmentItemValue|null one($db = null)
 * @method array|FulfillmentItemValue[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentItemValue
 */
class FulfillmentItemValueQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentItemValueId' => 'fulfillment_item_value_id',
                    'fulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'valueDate' => 'value_date',
                ]
            ]
        ];
    }

    /**
     * @param string $date
     * @return $this
     * @inheritdoc
     */
    public function valueDateBefore(string $date): self
    {
        //for calculate average item value or other, the inbound day should not use the new item value
        return $this->andWhere(['<', 'value_date', $date])->orderBy(['value_date' => SORT_DESC]);
    }
}
