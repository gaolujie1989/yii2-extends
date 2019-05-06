<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\base\Component;
use yii\console\Exception;
use yii\db\BaseActiveRecord;

/**
 * Class BaseStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseStockManager extends Component implements StockManagerInterface
{
    const EVENT_BEFORE_STOCK_MOVEMENT = 'beforeStockMovement';
    const EVENT_AFTER_STOCK_MOVEMENT = 'afterStockMovement';

    public $itemIdAttribute = 'item_id';
    public $locationIdAttribute = 'location_id';
    public $qtyAttribute = 'qty';
    public $reasonAttribute = 'reason';

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function inbound($itemId, $locationId, int $qty, $extraData = [])
    {
        if ($qty <= 0) {
            return false;
        }
        return $this->moveStock($itemId, $locationId, $qty, StockConst::MOVEMENT_REASON_INBOUND, $extraData);
    }

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function outbound($itemId, $locationId, int $qty, $extraData = [])
    {
        if ($qty <= 0) {
            return false;
        }
        return $this->moveStock($itemId, $locationId, -$qty, StockConst::MOVEMENT_REASON_INBOUND, $extraData);
    }

    /**
     * @param $itemId
     * @param $fromLocationId
     * @param $toLocationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function transfer($itemId, $fromLocationId, $toLocationId, int $qty, $extraData = [])
    {
        if ($qty <= 0) {
            return false;
        }

        $this->moveStock($itemId, $fromLocationId, -$qty, StockConst::MOVEMENT_REASON_TRANSFER_OUT, $extraData);
        $this->moveStock($itemId, $toLocationId, $qty, StockConst::MOVEMENT_REASON_TRANSFER_IN, $extraData);
    }

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @throws \Throwable
     * @inheritdoc
     */
    public function correct($itemId, $locationId, int $qty, $extraData = [])
    {
        $stock = $this->getStock($itemId, $locationId);
        $stockQty = $stock->getAttribute($this->qtyAttribute) ?: 0;
        $moveQty = $qty - $stockQty;
        $this->moveStock($itemId, $locationId, $moveQty, StockConst::MOVEMENT_REASON_CORRECT, $extraData);
    }

    /**
     * @param $itemId
     * @param $locationId
     * @return bool
     * @inheritdoc
     */
    abstract public function calculateStock($itemId, $locationId);

    /**
     * @param $itemId
     * @param $locationId
     * @return BaseActiveRecord
     * @inheritdoc
     */
    abstract public function getStock($itemId, $locationId);

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return mixed
     * @throws \Throwable
     * @inheritdoc
     */
    protected function moveStock($itemId, $locationId, int $qty, $reason, $extraData = [])
    {
        $stock = $this->getStock($itemId, $locationId);
        if ($qty < 0) {
            $stockQty = $stock->getAttribute($this->qtyAttribute) ?: 0;
            if ($stockQty + $qty < 0) {
                throw new Exception("No enough stocks of {$itemId} in {$locationId}");
            }
        }

        $event = new StockMovementEvent([
            'stock' => $stock,
            'itemId' => $itemId,
            'locationId' => $locationId,
            'qty' => $qty,
            'reason' => $reason,
            'extraData' => $extraData,
        ]);
        $this->trigger(self::EVENT_BEFORE_STOCK_MOVEMENT, $event);
        if (!$event->isValid) {
            return false;
        }

        $result = false;
        $createdMovement = $this->createStockMovement($itemId, $locationId, $qty, $reason, $extraData);
        if ($createdMovement) {
            if ($stock->getIsNewRecord()) {
                $stock->setAttributes([$this->qtyAttribute => $qty]);
                $result = $stock->save(false);
            } else {
                $result = $stock->updateCounters([$this->qtyAttribute => $qty]);
            }
        }

        $event->stockMovement = $createdMovement;
        $this->trigger(self::EVENT_AFTER_STOCK_MOVEMENT, $event);
        return $result;
    }

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return bool
     */
    abstract protected function createStockMovement($itemId, $locationId, int $qty, $reason, $extraData = []);
}
