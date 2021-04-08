<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use lujie\extend\helpers\TransactionHelper;
use Yii;
use yii\base\Component;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;

/**
 * Class BaseStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseStockManager extends Component implements StockManagerInterface
{
    public const EVENT_BEFORE_MOVEMENT = 'beforeMovement';
    public const EVENT_AFTER_MOVEMENT = 'afterMovement';

    public $itemIdAttribute = 'item_id';
    public $locationIdAttribute = 'location_id';
    public $stockQtyAttribute = 'stock_qty';

    public $movementIdAttribute = 'stock_movement_id';
    public $movedQtyAttribute = 'moved_qty';
    public $reasonAttribute = 'reason';

    #region Base common methods

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return array|BaseActiveRecord|null
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function moveStock(int $itemId, int $locationId, int $qty, string $reason, array $data = [])
    {
        $message = "Move quantity {$qty} of item {$itemId} in location {$locationId} by {$reason}";
        Yii::info($message, __METHOD__);
        $stockQty = $this->getStockQty($itemId, $locationId);
        if ($qty < 0 && $stockQty + $qty < 0) {
            $message = "Move quantity {$qty} of {$itemId} in {$locationId} more than available stock {$stockQty}";
            $exception = new MovementException($message);
            $exception->itemId = $itemId;
            $exception->locationId = $locationId;
            $exception->stockQty = $stockQty;
            $exception->movedQty = $qty;
            throw $exception;
        }

        return TransactionHelper::transaction(function () use ($itemId, $locationId, $qty, $reason, $data) {
            return $this->moveStockInternal($itemId, $locationId, $qty, $reason, $data);
        }, $this->getDb(), null);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return array|BaseActiveRecord|null
     * @throws \Exception
     * @inheritdoc
     */
    protected function moveStockInternal(int $itemId, int $locationId, int $qty, string $reason, array $data = [])
    {
        $stockQty = $this->getStockQty($itemId, $locationId);

        $messagePrefix = "Move quantity {$qty} of item {$itemId} in location {$locationId} by {$reason}";
        Yii::info($messagePrefix . ', trigger beforeMovement event', __METHOD__);
        $event = new StockMovementEvent();
        $event->itemId = $itemId;
        $event->locationId = $locationId;
        $event->stockQty = $stockQty;
        $event->moveQty = $qty;
        $event->reason = $reason;
        $event->additional = $data;
        $this->trigger(self::EVENT_BEFORE_MOVEMENT, $event);
        if (!$event->isValid) {
            Yii::info($messagePrefix . ', beforeMovement event valid', __METHOD__);
            return null;
        }

        Yii::info($messagePrefix . ', update stock quantity', __METHOD__);
        if (!$this->updateStockQty($itemId, $locationId, $qty)) {
            Yii::warning($messagePrefix . ', update stock quantity failed', __METHOD__);
            $this->trigger(self::EVENT_AFTER_MOVEMENT, $event);
            return null;
        }

        Yii::info($messagePrefix . ', create movement', __METHOD__);
        $createdMovement = $this->createStockMovement($event->itemId, $event->locationId, $event->moveQty, $event->reason, $event->additional);
        $event->stockMovement = $createdMovement;

        Yii::info($messagePrefix . ', trigger afterMovement event', __METHOD__);
        $this->trigger(self::EVENT_AFTER_MOVEMENT, $event);
        return $createdMovement;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return int
     * @throws \Exception
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
     * @param int $accountId
     * @param string $transactionType
     * @param int $amount
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function getMovementData(int $itemId, int $locationId, int $qty, string $reason): array
    {
        return [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->movedQtyAttribute => $qty,
            $this->stockQtyAttribute => $this->getStockQty($itemId, $locationId),
            $this->reasonAttribute => $reason,
        ];
    }

    #endregion

    #region Interface implements

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return array|mixed|BaseActiveRecord|null
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function inbound(int $itemId, int $locationId, int $qty, array $data = [])
    {
        if ($qty <= 0) {
            return null;
        }
        return $this->moveStock($itemId, $locationId, $qty, StockConst::MOVEMENT_REASON_INBOUND, $data);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return array|mixed|BaseActiveRecord|null
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function outbound(int $itemId, int $locationId, int $qty, array $data = [])
    {
        if ($qty <= 0) {
            return null;
        }
        return $this->moveStock($itemId, $locationId, -$qty, StockConst::MOVEMENT_REASON_OUTBOUND, $data);
    }

    /**
     * @param int $itemId
     * @param int $fromLocationId
     * @param int $toLocationId
     * @param int $qty
     * @param array $data
     * @return array|null
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function transfer(int $itemId, int $fromLocationId, int $toLocationId, int $qty, array $data = []): ?array
    {
        if ($qty <= 0) {
            return null;
        }

        return TransactionHelper::transaction(function () use ($itemId, $fromLocationId, $toLocationId, $qty, $data) {
            $fromMovement = $this->moveStock($itemId, $fromLocationId, -$qty, StockConst::MOVEMENT_REASON_TRANSFER_OUT, $data);
            if ($fromMovement === null) {
                return null;
            }
            $toMovement = $this->moveStock($itemId, $toLocationId, $qty, StockConst::MOVEMENT_REASON_TRANSFER_IN, $data);
            if ($toMovement === null) {
                return null;
            }
            return [$fromMovement, $toMovement];
        }, $this->getDb(), null);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return array|mixed|BaseActiveRecord|null
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function correct(int $itemId, int $locationId, int $qty, array $data = [])
    {
        $stockQty = $this->getStockQty($itemId, $locationId);
        $moveQty = $qty - $stockQty;
        return $this->moveStock($itemId, $locationId, $moveQty, StockConst::MOVEMENT_REASON_CORRECT, $data);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $fromMovementId
     * @return int
     * @throws \Exception
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId, int $fromMovementId = 0): int
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $query = $this->movementQuery()
            ->andWhere($condition)
            ->andWhere(['>', $this->movementIdAttribute, $fromMovementId]);
        $totalStockQty = $query->sum($this->movedQtyAttribute);

        if ($fromMovementId <= 0) {
            return $totalStockQty;
        }

        $movement = $this->movementQuery()->andWhere([$this->movementIdAttribute => $fromMovementId])->one();
        $movementStockQty = ArrayHelper::getValue($movement, $this->stockQtyAttribute);
        return $movementStockQty + $totalStockQty;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId)
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        return $this->stockQuery()->andWhere($condition)->one() ?: null;
    }

    #endregion

    #region Abstract functions

    /**
     * @return Connection|mixed
     * @inheritdoc
     */
    abstract protected function getDb();

    /**
     * @return QueryInterface
     * @inheritdoc
     */
    abstract protected function stockQuery(): QueryInterface;

    /**
     * @return QueryInterface
     * @inheritdoc
     */
    abstract protected function movementQuery(): QueryInterface;

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return mixed|null
     * @inheritdoc
     */
    abstract protected function createStockMovement(int $itemId, int $locationId, int $qty, string $reason, array $data = []);

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $moveQty
     * @return bool
     * @inheritdoc
     */
    abstract protected function updateStockQty(int $itemId, int $locationId, int $moveQty): bool;

    /**
     * @param int $itemId
     * @param int $locationId
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    abstract public function updateStock(int $itemId, int $locationId, array $data): bool;

    #endregion

}
