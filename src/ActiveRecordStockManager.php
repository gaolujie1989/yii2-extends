<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


use yii\base\Component;
use yii\base\ModelEvent;
use yii\console\Exception;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Query;

/**
 * Class ActiveRecordStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordStockManager extends BaseStockManager
{
    /**
     * @var BaseActiveRecord
     */
    public $stockClass;
    /**
     * @var BaseActiveRecord
     */
    public $stockMovementClass;

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
        $callable = function() use ($itemId, $fromLocationId, $toLocationId, $qty, $extraData) {
            parent::transfer($itemId, $fromLocationId, $toLocationId, $qty, $extraData);
        };
        $db = $this->stockMovementClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        } else {
            return call_user_func($callable);
        }
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
        $query = $this->stockMovementClass::find()
            ->andWhere([
                $this->itemIdAttribute => $itemId,
                $this->locationIdAttribute => $locationId,
            ]);
        $stockQty = $query->sum($this->movedQtyAttribute);
        $stock->setAttribute($this->stockQtyAttribute, $stockQty);
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
        return $this->stockClass::findOne($condition) ?: new $this->stockClass($condition);
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
            parent::moveStock($itemId, $locationId, $qty, $reason, $extraData);
        };
        $db = $this->stockMovementClass::getDb();
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
     * @return bool|BaseActiveRecord
     * @inheritdoc
     */
    protected function createStockMovement($itemId, $locationId, int $qty, $reason, $extraData = [])
    {
        /** @var BaseActiveRecord $stockMovement */
        $stockMovement = new $this->stockMovementClass();
        $stockMovement->setAttributes([
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->movedQtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ]);
        $stockMovement->setAttributes($extraData);
        $stockMovement->save(false);
        return $stockMovement;
    }
}
