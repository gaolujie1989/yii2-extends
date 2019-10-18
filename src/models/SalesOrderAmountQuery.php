<?php

namespace lujie\sales\order\center\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[SalesOrderAmount]].
 *
 * @method SalesOrderAmountQuery id($id)
 * @method SalesOrderAmountQuery salesOrderId($salesOrderId)
 * @method SalesOrderAmountQuery salesOrderItemId($salesOrderItemId)
 * @method SalesOrderAmountQuery currency($currency)
 *
 * @method SalesOrderAmountQuery orderAmount()
 * @method SalesOrderAmountQuery orderItemAmount()
 *
 * @method array|SalesOrderAmount[] all($db = null)
 * @method array|SalesOrderAmount|null one($db = null)
 *
 * @see SalesOrderAmount
 */
class SalesOrderAmountQuery extends \yii\db\ActiveQuery
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
                    'salesOrderId' => 'sales_order_id',
                    'salesOrderItemId' => 'sales_order_item_id',
                    'currency' => 'currency',
                ],
                'queryConditions' => [
                    'orderAmount' => ['sales_order_item_id' => 0],
                    'orderItemAmount' => ['>', 'sales_order_item_id', 0],
                ]
            ]
        ];
    }
}
