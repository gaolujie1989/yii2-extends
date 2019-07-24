<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use lujie\extend\helpers\TransactionHelper;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\console\Exception;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * Class BaseStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseStockManager extends Component implements StockManagerInterface
{
    public const EVENT_BEFORE_STOCK_MOVEMENT = 'beforeStockMovement';
    public const EVENT_AFTER_STOCK_MOVEMENT = 'afterStockMovement';

    public $itemIdAttribute = 'item_id';
    public $locationIdAttribute = 'location_id';
    public $stockQtyAttribute = 'stock_qty';
    public $moveQtyAttribute = 'move_qty';
    public $reasonAttribute = 'reason';

    /**
     * @return Connection|mixed
     * @inheritdoc
     */
    abstract protected function getDb();

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function inbound(int $itemId, int $locationId, int $qty, $extraData = []): bool
    {
        if ($qty <= 0) {
            return false;
        }
        return $this->moveStock($itemId, $locationId, $qty, StockConst::MOVEMENT_REASON_INBOUND, $extraData);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function outbound(int $itemId, int $locationId, int $qty, $extraData = []): bool
    {
        if ($qty <= 0) {
            return false;
        }
        return $this->moveStock($itemId, $locationId, -$qty, StockConst::MOVEMENT_REASON_OUTBOUND, $extraData);
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
    public function transfer(int $itemId, int $fromLocationId, int $toLocationId, int $qty, array $extraData = []): bool
    {
        if ($qty <= 0) {
            return false;
        }

        return TransactionHelper::transaction(static function () use ($itemId, $fromLocationId, $toLocationId, $qty, $extraData) {
            return $this->moveStock($itemId, $fromLocationId, -$qty, StockConst::MOVEMENT_REASON_TRANSFER_OUT, $extraData)
                && $this->moveStock($itemId, $toLocationId, $qty, StockConst::MOVEMENT_REASON_TRANSFER_IN, $extraData);
        }, $this->getDb());
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function correct(int $itemId, int $locationId, int $qty, array $extraData = []): bool
    {
        $stockQty = $this->getStockQty($itemId, $locationId);
        $moveQty = $qty - $stockQty;
        return $this->moveStock($itemId, $locationId, $moveQty, StockConst::MOVEMENT_REASON_CORRECT, $extraData);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return int|mixed
     * @inheritdoc
     */
    protected function getStockQty(int $itemId, int $locationId): int
    {
        $stock = $this->getStock($itemId, $locationId);
        if (empty($stock)) {
            return 0;
        }
        return ArrayHelper::getValue($stock, $this->stockQtyAttribute);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return bool
     * @throws Exception
     * @throws \Throwable
     * @inheritdoc
     */
    protected function moveStock(int $itemId, int $locationId, int $qty, $reason, $extraData = []): bool
    {
        $stockQty = $this->getStockQty($itemId, $locationId);
        if ($qty < 0 && $stockQty + $qty < 0) {
            $message = "Not enough stocks of {$itemId} in {$locationId}, only exist {$stockQty}, can not move {$qty}";
            throw new InvalidArgumentException($message);
        }

        return TransactionHelper::transaction(static function () use ($itemId, $locationId, $qty, $reason, $extraData) {
            return $this->moveStockInternal($itemId, $locationId, $qty, $reason, $extraData);
        }, $this->getDb());
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return bool
     * @inheritdoc
     */
    protected function moveStockInternal(int $itemId, int $locationId, int $qty, $reason, $extraData = []): bool
    {
        $stockQty = $this->getStockQty($itemId, $locationId);
        $event = new StockMovementEvent([
            'itemId' => $itemId,
            'locationId' => $locationId,
            'stockQty' => $stockQty,
            'moveQty' => $qty,
            'reason' => $reason,
            'extraData' => $extraData,
        ]);
        $this->trigger(self::EVENT_BEFORE_STOCK_MOVEMENT, $event);
        if (!$event->isValid) {
            return false;
        }

        $result = false;
        $createdMovement = $this->createStockMovement($itemId, $locationId, $qty, $reason, $event->extraData);
        if ($createdMovement) {
            $result = $this->updateStockQty($itemId, $locationId, $qty);
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
     * @return array|BaseActiveRecord
     * @inheritdoc
     */
    abstract protected function createStockMovement(int $itemId, int $locationId, int $qty, $reason, $extraData = []);

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $moveQty
     * @return bool
     * @inheritdoc
     */
    abstract protected function updateStockQty(int $itemId, int $locationId, int $moveQty): bool;

    /**
     * @param $item
     * @param $locationId
     * @param $moveQty
     * @return bool
     * @inheritdoc
     */
    abstract public function updateStock(int $itemId, int $locationId, array $data): bool;
}
