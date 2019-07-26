<?php

namespace lujie\fulfillment\models;

use Generator;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouse]].
 *
 * @method FulfillmentWarehouseQuery accountId($accountId)
 * @method FulfillmentWarehouseQuery warehouseId($warehouseId)
 * @method FulfillmentWarehouseQuery externalWarehouseId($externalWarehouseId)
 * @method FulfillmentWarehouseQuery externalWarehouseName($externalWarehouseName)
 *
 * @method array|FulfillmentWarehouse[] all($db = null)
 * @method array|FulfillmentWarehouse|null one($db = null)
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
                    'accountId' => 'fulfillment_account_id',
                    'warehouseId' => 'warehouse_id',
                    'externalWarehouseId' => 'external_warehouse_id',
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
}
