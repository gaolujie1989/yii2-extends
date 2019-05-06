<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


use yii\base\Behavior;
use yii\base\InvalidArgumentException;

/**
 * Class StockValueBehavior
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StockValueBehavior extends Behavior
{
    public $stockValueAttribute = 'stock_value';

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecordStockManager::EVENT_AFTER_STOCK_MOVEMENT => 'afterStockMovement'
        ];
    }

    /**
     * @param StockMovementEvent $event
     * @inheritdoc
     */
    public function afterStockMovement(StockMovementEvent $event)
    {
        /** @var ActiveRecordStockManager $stockManager */
        $stockManager = $event->sender;
        $movedQty = $event->stockMovement->getAttribute($stockManager->qtyAttribute);
        $moveReason = $event->stockMovement->getAttribute($stockManager->reasonAttribute);
        if ($moveReason != StockConst::MOVEMENT_REASON_INBOUND) {
            return;
        }

        if (empty($event->extraData[$this->stockValueAttribute])) {
            throw new InvalidArgumentException("Inbound extra data {$this->stockValueAttribute} must be set");
        }

        $stock = $event->stock;
        $movedStockValue = $event->extraData[$this->stockValueAttribute];
        $oldStockValue = $stock->getAttribute($this->stockValueAttribute);
        $newStockQty = $stock->getAttribute($stockManager->qtyAttribute);
        $oldStockQty = $newStockQty - $movedQty;

        $newStockValue = round((($oldStockValue * $oldStockQty) + ($movedStockValue * $movedQty)) / $newStockQty, 2);
        $stock->setAttributes([$this->stockValueAttribute => $newStockValue]);
        $stock->save(false);
    }
}
