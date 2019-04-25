<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stocking;


use yii\base\Behavior;

/**
 * Class StockValueBehavior
 * @package lujie\stocking
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
            StockManager::EVENT_AFTER_STOCK_MOVEMENT => 'afterStockMovement'
        ];
    }

    /**
     * @param StockMovementEvent $event
     * @inheritdoc
     */
    public function afterStockMovement(StockMovementEvent $event)
    {
        /** @var StockManager $stockManager */
        $stockManager = $event->sender;
        $movedQty = $event->stockMovement->getAttribute($stockManager->qtyAttribute);
        if ($movedQty < 0) {
            return;
        }

        $stock = $event->stock;
        $oldStockValue = $stock->getAttribute($this->stockValueAttribute);
        $newStockQty = $stock->getAttribute($stockManager->qtyAttribute);
        $oldStockQty = $newStockQty - $movedQty;

        $movedStockValue = $event->extraData[$this->stockValueAttribute];
        $newStockValue = round((($oldStockValue * $oldStockQty) + ($movedStockValue * $movedQty)) / $newStockQty, 2);
        $stock->setAttributes([$this->stockValueAttribute => $newStockValue]);
        $stock->save(false);
    }
}
