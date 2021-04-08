<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class StockValueBehavior
 *
 * @property BaseStockManager $owner
 *
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StockValueBehavior extends Behavior
{
    /**
     * @var string
     */
    public $movedItemValueAttribute = 'item_value_cent';

    /**
     * @var string
     */
    public $stockItemValueAttribute = 'item_value_cent';

    /**
     * @var int
     */
    public $itemValueRoundPrecision = 0;

    /**
     * @var float
     */
    private $transferOutItemValue;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecordStockManager::EVENT_BEFORE_MOVEMENT => 'beforeStockMovement',
            ActiveRecordStockManager::EVENT_AFTER_MOVEMENT => 'afterStockMovement',
        ];
    }

    /**
     * set Transfer out item value, for transfer in to average item value
     * @param StockMovementEvent $event
     * @throws \Exception
     * @inheritdoc
     */
    public function beforeStockMovement(StockMovementEvent $event): void
    {
        //set transferOut stock item value, using in transferIn
        if ($event->reason === StockConst::MOVEMENT_REASON_TRANSFER_OUT) {
            $this->transferOutItemValue = $this->getStockValue($event->itemId, $event->locationId);
            return;
        }
        if ($event->reason === StockConst::MOVEMENT_REASON_TRANSFER_IN) {
            if (empty($this->transferOutItemValue)) {
                //normally should not execute it here, so throw exception
                throw new InvalidArgumentException('Transfer out item value must be set');
            }
            //set move item value, so it will save in stock movement
            $event->additional[$this->movedItemValueAttribute] = $this->transferOutItemValue;
            $this->transferOutItemValue = null;
        }
    }

    /**
     * Average item value, for transfer in, the item value has validate in beforeStockMovement, only check inbound
     * @param StockMovementEvent $event
     * @throws \Exception
     * @inheritdoc
     */
    public function afterStockMovement(StockMovementEvent $event): void
    {
        $effectValueReasons = [StockConst::MOVEMENT_REASON_INBOUND, StockConst::MOVEMENT_REASON_TRANSFER_IN];
        if (empty($event->stockMovement) || !in_array($event->reason, $effectValueReasons, true)) {
            return;
        }

        if ($event->reason === StockConst::MOVEMENT_REASON_INBOUND
            && !is_numeric($event->additional[$this->movedItemValueAttribute] ?? 0)) {
            throw new InvalidArgumentException("Movement additional data {$this->movedItemValueAttribute} should be a number");
        }

        $movedStockValue = $event->additional[$this->movedItemValueAttribute];
        $movedQty = $event->moveQty;
        $oldStockValue = $this->getStockValue($event->itemId, $event->locationId);
        $oldStockQty = $event->stockQty;
        $newStockQty = $event->stockQty + $event->moveQty;

        $newStockValue = (($oldStockValue * $oldStockQty) + ($movedStockValue * $movedQty)) / $newStockQty;
        $newStockValue = round($newStockValue, $this->itemValueRoundPrecision);
        $this->owner->updateStock($event->itemId, $event->locationId, [$this->stockItemValueAttribute => $newStockValue]);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return float|int
     * @throws \Exception
     * @inheritdoc
     */
    protected function getStockValue(int $itemId, int $locationId)
    {
        $stock = $this->owner->getStock($itemId, $locationId);
        if (empty($stock)) {
            return 0;
        }
        return ArrayHelper::getValue($stock, $this->stockItemValueAttribute) ?: 0;
    }
}
