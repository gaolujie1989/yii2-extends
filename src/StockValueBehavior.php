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
    public $stockValueAttribute = 'stock_value';

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecordStockManager::EVENT_AFTER_STOCK_MOVEMENT => 'afterStockMovement'
        ];
    }

    /**
     * @param StockMovementEvent $event
     * @inheritdoc
     */
    public function afterStockMovement(StockMovementEvent $event): void
    {
        if ($event->reason !== StockConst::MOVEMENT_REASON_INBOUND) {
            return;
        }

        if (empty($event->extraData[$this->stockValueAttribute])) {
            throw new InvalidArgumentException("Inbound extra data {$this->stockValueAttribute} must be set");
        }

        $oldStockValue = $this->getStockValue($event->itemId, $event->locationId);
        $oldStockQty = $event->stockQty;
        $movedStockValue = $event->extraData[$this->stockValueAttribute];
        $movedQty = $event->moveQty;
        $newStockQty = $event->stockQty + $event->moveQty;

        $newStockValue = round((($oldStockValue * $oldStockQty) + ($movedStockValue * $movedQty)) / $newStockQty, 2);
        $this->owner->updateStock($event->itemId, $event->locationId, [$this->stockValueAttribute => $newStockValue]);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return int
     * @inheritdoc
     */
    protected function getStockValue(int $itemId, int $locationId): int
    {
        $stock = $this->owner->getStock($itemId, $locationId);
        if (empty($stock)) {
            return 0;
        }
        return ArrayHelper::getValue($stock, $this->stockValueAttribute);
    }
}
