<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

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
 * @method FulfillmentWarehouseQuery externalMovementAtBefore($externalMovementAtBefore)
 *
 * @method FulfillmentWarehouseQuery supportMovement()
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
                    'externalMovementAtBefore' => ['external_movement_at' => '<='],
                ],
                'queryConditions' => [
                    'supportMovement' => ['support_movement' => StatusConst::STATUS_ACTIVE]
                ],
            ]
        ];
    }

    /**
     * @param bool $indexByExternalWarehouseKey
     * @return array
     * @inheritdoc
     */
    public function getWarehouseIds(bool $indexByExternalWarehouseKey = true): array
    {
        if ($indexByExternalWarehouseKey) {
            $this->indexBy('external_warehouse_key');
        }
        return $this->select(['warehouse_id'])->column();
    }
}
