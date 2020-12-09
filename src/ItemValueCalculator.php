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
abstract class ItemValueCalculator extends BaseObject
{
    public $currency = 'EUR';

    /**
     * @param int $warehouseId
     * @param string $dateFrom
     * @throws Exception
     * @inheritdoc
     */
    public function calculateMovementsItemValues(int $warehouseId, string $dateFrom): void
    {
        $movementQuery = FulfillmentDailyStockMovement::find()
            ->warehouseId($warehouseId)
            ->movementDateFrom($dateFrom)
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
            ->warehouseId($dailyStockMovement->warehouse_id)
            ->itemId($dailyStockMovement->item_id)
            ->stockDate($dailyStockMovement->movement_date)
            ->one();
        if ($dailyStock === null) {
            throw new Exception('Daily stock movement with null daily stock');
        }

        $newItemValue = FulfillmentItemValue::find()
            ->fulfillmentDailyStockMovementId($dailyStockMovement->fulfillment_daily_stock_movement_id)
            ->warehouseId($dailyStockMovement->warehouse_id)
            ->itemId($dailyStockMovement->item_id)
            ->valueDate($dailyStockMovement->movement_date)
            ->one();
        if ($newItemValue === null) {
            $newItemValue = new FulfillmentItemValue();
            $newItemValue->fulfillment_daily_stock_movement_id = $dailyStockMovement->fulfillment_daily_stock_movement_id;
            $newItemValue->warehouse_id = $dailyStockMovement->warehouse_id;
            $newItemValue->item_id = $dailyStockMovement->item_id;
            $newItemValue->external_warehouse_key = $dailyStockMovement->external_warehouse_key;
            $newItemValue->external_item_key = $dailyStockMovement->external_item_key;
            $newItemValue->value_date = $dailyStockMovement->movement_date;
            $newItemValue->currency = $this->currency;
        }
        $newItemValue->inbound_item_qty = $dailyStockMovement->movement_qty;
        $newItemValue->inbound_item_value_cent = $this->getItemValue($dailyStockMovement->item_id, $dailyStockMovement->movement_date);

        $newItemValue->old_item_qty = max($dailyStock->available_qty - $dailyStockMovement->movement_qty, 0);
        $oldItemValue = FulfillmentItemValue::find()
            ->warehouseId($dailyStockMovement->warehouse_id)
            ->itemId($dailyStockMovement->item_id)
            ->valueDateBefore($dailyStockMovement->movement_date)
            ->orderByValueDate(SORT_DESC)
            ->one();
        $newItemValue->old_item_value_cent = $oldItemValue === null ? 0 : $oldItemValue->new_item_value_cent;

        $newItemValue->new_item_qty = $dailyStock->available_qty;
        $newItemValue->new_item_value_cent = (int)round((
                ($newItemValue->old_item_value_cent * $newItemValue->old_item_qty)
                + ($newItemValue->inbound_item_value_cent * $newItemValue->inbound_item_qty)
            ) / $newItemValue->new_item_qty);

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