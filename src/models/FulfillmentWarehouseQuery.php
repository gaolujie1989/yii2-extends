<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouse]].
 *
 * @method FulfillmentWarehouseQuery id($id)
 * @method FulfillmentWarehouseQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseQuery fulfillmentWarehouseId($fulfillmentWarehouseId)
 * @method FulfillmentWarehouseQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseQuery warehouseId($warehouseId)
 * @method FulfillmentWarehouseQuery externalWarehouseKey($externalWarehouseKey)
 *
 * @method array|FulfillmentWarehouse[] all($db = null)
 * @method array|FulfillmentWarehouse|null one($db = null)
 * @method array|FulfillmentWarehouse[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentWarehouse
 */
class FulfillmentWarehouseQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentWarehouseId' => 'fulfillment_warehouse_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'warehouseId' => 'warehouse_id',
                    'externalWarehouseKey' => 'external_warehouse_key',
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getWarehouseIdsIndexByExternalWarehouseKey(): array
    {
        return $this->select(['warehouse_id'])->indexBy('external_warehouse_key')->asArray()->column();
    }
}
