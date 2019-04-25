<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stocking;


use yii\base\Component;
use yii\base\ModelEvent;
use yii\console\Exception;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Query;

/**
 * Class CalculateStockManager
 * @package lujie\stocking
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StockManager extends Component implements StockManagerInterface
{
    const EVENT_BEFORE_STOCK_MOVEMENT = 'beforeStockMovement';
    const EVENT_AFTER_STOCK_MOVEMENT = 'afterStockMovement';

    /**
     * @var BaseActiveRecord
     */
    public $stockModelClass;
    /**
     * @var BaseActiveRecord
     */
    public $stockMovementModelClass;

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

        $callable = function () use ($itemId, $fromLocationId, $toLocationId, $qty, $extraData) {
            $this->moveStock($itemId, $fromLocationId, -$qty, StockConst::MOVEMENT_REASON_TRANSFER_OUT, $extraData);
            $this->moveStock($itemId, $toLocationId, $qty, StockConst::MOVEMENT_REASON_TRANSFER_IN, $extraData);
        };

        $db = $this->stockMovementModelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        } else {
            return call_user_func($callable);
        }
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
    public function calculateStock($itemId, $locationId)
    {
        $stock = $this->getStock($itemId, $locationId);
        /** @var Query $query */
        $query = $this->stockMovementModelClass::find()
            ->andWhere([
                $this->itemIdAttribute => $itemId,
                $this->locationIdAttribute => $locationId,
            ]);
        $stockQty = $query->sum($this->qtyAttribute);
        $stock->setAttribute($this->qtyAttribute, $stockQty);
        return $stock->save(false);
    }

    /**
     * @param $itemId
     * @param $locationId
     * @return BaseActiveRecord
     * @inheritdoc
     */
    public function getStock($itemId, $locationId)
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        /** @var BaseActiveRecord $stock */
        return $this->stockModelClass::findOne($condition) ?: new $this->stockModelClass($condition);
    }

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
        $callable = function () use ($itemId, $locationId, $qty, $reason, $extraData) {
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
        };
        $db = $this->stockMovementModelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        } else {
            return call_user_func($callable);
        }
    }

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return bool
     */
    protected function createStockMovement($itemId, $locationId, int $qty, $reason, $extraData = [])
    {
        /** @var BaseActiveRecord $stockMovement */
        $stockMovement = new $this->stockMovementModelClass();
        $stockMovement->setAttributes([
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->qtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ]);
        $stockMovement->setAttributes($extraData);
        return $stockMovement->save(false);
    }
}
