<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

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
 * @method FulfillmentWarehouseQuery externalWarehouseId($externalWarehouseId)
 * @method FulfillmentWarehouseQuery status($status)
 *
 * @method FulfillmentWarehouseQuery accountId($accountId)
 * @method FulfillmentWarehouseQuery externalWarehouseName($externalWarehouseName)
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
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fulfillmentWarehouseId' => 'fulfillment_warehouse_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'warehouseId' => 'warehouse_id',
                    'externalWarehouseId' => 'external_warehouse_id',
                    'status' => 'status',

                    'accountId' => 'fulfillment_account_id',
                    'externalWarehouseName' => 'external_warehouse_name',
                ]
            ]
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getWarehouseIdsIndexByExternalWarehouseId(): array
    {
        $warehouses = $this->select(['warehouse_id', 'external_warehouse_id'])->asArray()->all();
        return ArrayHelper::map($warehouses, 'external_warehouse_id', 'warehouse_id');
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getWarehouseIdsIndexByExternalWarehouseName(): array
    {
        $warehouses = $this->select(['warehouse_id', 'external_warehouse_name'])->asArray()->all();
        return ArrayHelper::map($warehouses, 'external_warehouse_name', 'warehouse_id');
    }
}
