<?php

namespace lujie\common\shipping\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ShippingRule]].
 *
 * @method ShippingRuleQuery id($id)
 * @method ShippingRuleQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ShippingRuleQuery shippingRuleId($shippingRuleId)
 * @method ShippingRuleQuery itemId($itemId)
 * @method ShippingRuleQuery warehouseId($warehouseId)
 * @method ShippingRuleQuery status($status)
 * @method ShippingRuleQuery ownerId($ownerId)
 *
 * @method array|ShippingRule[] all($db = null)
 * @method array|ShippingRule|null one($db = null)
 * @method array|ShippingRule[] each($batchSize = 100, $db = null)
 *
 * @see ShippingRule
 */
class ShippingRuleQuery extends \yii\db\ActiveQuery
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
                    'shippingRuleId' => 'shipping_rule_id',
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'status' => 'status',
                    'ownerId' => 'owner_id',
                ]
            ]
        ];
    }

}
