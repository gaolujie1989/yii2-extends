<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentDailyStock;
use lujie\fulfillment\models\FulfillmentDailyStockMovement;
use lujie\fulfillment\models\FulfillmentItemValue;
use yii\base\BaseObject;
use yii\base\Exception;

/**
 * Class ItemValueCalculator
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class FulfillmentItemValueCalculator extends BaseObject
{
    public $currency = 'EUR';

    /**
     * @param int $warehouseId
     * @param string $dateFrom
     * @param string $dateTo
     * @throws Exception
     * @inheritdoc
     */
    public function calculateMovementsItemValues(string $externalWarehouseKey, string $dateFrom, string $dateTo): void
    {
        $movementQuery = FulfillmentDailyStockMovement::find()
            ->externalWarehouseKey($externalWarehouseKey)
            ->movementDateBetween($dateFrom, $dateTo)
            ->orderByMovementDate();
        foreach ($movementQuery->each() as $dailyStockMovement) {
            $this->calculateItemValue($dailyStockMovement);
        }
    }

    /**
     * @param FulfillmentDailyStockMovement $dailyStockMovement
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function calculateItemValue(FulfillmentDailyStockMovement $dailyStockMovement): bool
    {
        if ($dailyStockMovement->movement_type !== FulfillmentConst::MOVEMENT_TYPE_INBOUND) {
            return false;
        }
        $dailyStock = FulfillmentDailyStock::find()
            ->externalWarehouseKey($dailyStockMovement->external_warehouse_key)
            ->externalItemKey($dailyStockMovement->external_item_key)
            ->stockDate($dailyStockMovement->movement_date)
            ->one();
        if ($dailyStock === null) {
            $message = "Null daily stock of Item {$dailyStockMovement->external_item_key}"
                . " in warehouse {$dailyStockMovement->external_warehouse_key} at {$dailyStockMovement->movement_date}";
            throw new Exception($message);
        }

        $newItemValue = FulfillmentItemValue::find()
            ->fulfillmentDailyStockMovementId($dailyStockMovement->fulfillment_daily_stock_movement_id)
            ->externalWarehouseKey($dailyStockMovement->external_warehouse_key)
            ->externalItemKey($dailyStockMovement->external_item_key)
            ->valueDate($dailyStockMovement->movement_date)
            ->one();
        if ($newItemValue === null) {
            $newItemValue = new FulfillmentItemValue();
            $newItemValue->fulfillment_daily_stock_movement_id = $dailyStockMovement->fulfillment_daily_stock_movement_id;
            $newItemValue->external_warehouse_key = $dailyStockMovement->external_warehouse_key;
            $newItemValue->external_item_key = $dailyStockMovement->external_item_key;
            $newItemValue->value_date = $dailyStockMovement->movement_date;
            $newItemValue->currency = $this->currency;
        }
        $newItemValue->inbound_item_qty = $dailyStockMovement->movement_qty;
        $newItemValue->inbound_item_value_cent = $this->getItemValue($dailyStockMovement->item_id, $dailyStockMovement->movement_date);

        $newItemValue->old_item_qty = max($dailyStock->stock_qty - $dailyStockMovement->movement_qty, 0);
        $oldItemValue = FulfillmentItemValue::find()
            ->externalWarehouseKey($dailyStockMovement->external_warehouse_key)
            ->externalItemKey($dailyStockMovement->external_item_key)
            ->valueDateBefore($dailyStockMovement->movement_date)
            ->orderByValueDate(SORT_DESC)
            ->one();
        $newItemValue->old_item_value_cent = $oldItemValue === null ? 0 : $oldItemValue->new_item_value_cent;

        $newItemValue->new_item_qty = $dailyStock->stock_qty;
        if ($newItemValue->old_item_value_cent && $newItemValue->inbound_item_value_cent && $newItemValue->new_item_qty > 0) {
            $newItemValue->new_item_value_cent = (int)round((
                    ($newItemValue->old_item_value_cent * $newItemValue->old_item_qty)
                    + ($newItemValue->inbound_item_value_cent * $newItemValue->inbound_item_qty)
                ) / $newItemValue->new_item_qty);
        } else {
            $newItemValue->new_item_value_cent = max($newItemValue->old_item_value_cent, $newItemValue->inbound_item_value_cent);
        }

        if ($oldItemValue !== null && $oldItemValue->latest) {
            $oldItemValue->latest = 0;
            $oldItemValue->save(false);
        }
        $newItemValue->latest = 1;
        return $newItemValue->save(false);
    }

    /**
     * @param int $itemId
     * @param string $date
     * @return int
     * @inheritdoc
     */
    abstract public function getItemValue(int $itemId, string $date): int;
}
