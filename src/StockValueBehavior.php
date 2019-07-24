<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\debug\FlattenException;
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
    public $movedItemValueAttribute = 'moved_item_value';

    /**
     * @var string
     */
    public $stockItemValueAttribute = 'stock_item_value';

    /**
     * @var int
     */
    public $itemValueRoundPrecision = 2;

    /**
     * @var array
     */
    private $transferOutItemValue;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecordStockManager::EVENT_BEFORE_STOCK_MOVEMENT => 'beforeStockMovement',
            ActiveRecordStockManager::EVENT_AFTER_STOCK_MOVEMENT => 'afterStockMovement',
        ];
    }

    /**
     * set Transfer out item value, for transfer in to average item value
     * @param StockMovementEvent $event
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
            $event->extraData[$this->movedItemValueAttribute] = $this->transferOutItemValue;
            $this->transferOutItemValue = null;
        }
    }

    /**
     * Average item value, for transfer in, the item value has validate in beforeStockMovement, only check inbound
     * @param StockMovementEvent $event
     * @inheritdoc
     */
    public function afterStockMovement(StockMovementEvent $event): void
    {
        $effectValueReasons = [StockConst::MOVEMENT_REASON_INBOUND, StockConst::MOVEMENT_REASON_TRANSFER_IN];
        if (empty($event->stockMovement) || !in_array($event->reason, $effectValueReasons, true)) {
            return;
        }

        if ($event->reason === StockConst::MOVEMENT_REASON_INBOUND
            && (!isset($event->extraData[$this->movedItemValueAttribute])
                && !is_numeric(!isset($event->extraData[$this->movedItemValueAttribute])))) {
            throw new InvalidArgumentException("Move extra data {$this->movedItemValueAttribute} should be a number");
        }

        $movedStockValue = $event->extraData[$this->movedItemValueAttribute];
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
     * @return float
     * @inheritdoc
     */
    protected function getStockValue(int $itemId, int $locationId): float
    {
        $stock = $this->owner->getStock($itemId, $locationId);
        if (empty($stock)) {
            return 0;
        }
        return ArrayHelper::getValue($stock, $this->stockItemValueAttribute) ?: 0;
    }
}
