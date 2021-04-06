<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

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

    #region Abstract functions implements

    /**
     * @return mixed|Connection
     * @inheritdoc
     */
    protected function getDb()
    {
        return $this->stockClass::getDb();
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return BaseActiveRecord
     * @inheritdoc
     */
    protected function createStockMovement(int $itemId, int $locationId, int $qty, string $reason, array $data = []): BaseActiveRecord
    {
        $movementData = array_merge($data, [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->movedQtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ]);
        /** @var BaseActiveRecord $stockMovement */
        $stockMovement = new $this->stockMovementClass();
        $stockMovement->setAttributes($movementData);
        $stockMovement->save(false);
        return $stockMovement;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $moveQty
     * @return bool
     * @inheritdoc
     */
    protected function updateStockQty(int $itemId, int $locationId, int $moveQty): bool
    {
        $stock = $this->getStock($itemId, $locationId);
        if ($stock->getIsNewRecord()) {
            $stock->setAttribute($this->stockQtyAttribute, $moveQty);
            return $stock->save(false);
        }
        return $stock->updateCounters([$this->stockQtyAttribute => $moveQty]);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function updateStock(int $itemId, int $locationId, array $data): bool
    {
        $stock = $this->getStock($itemId, $locationId);
        $stock->setAttributes($data);
        return $stock->save(false);
    }

    #endregion

    #region Interface implements

    /**
     * @param int $itemId
     * @param int $locationId
     * @return int
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): int
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        /** @var Query $query */
        $query = $this->stockMovementClass::find()->andWhere($condition);
        $stockQty = $query->sum($this->movedQtyAttribute);

        $this->updateStock($itemId, $locationId, [$this->stockQtyAttribute => $stockQty]);
        return $stockQty;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return BaseActiveRecord
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId): BaseActiveRecord
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        /** @var BaseActiveRecord $stock */
        $stock = $this->stockClass::findOne($condition) ?: new $this->stockClass($condition);
        if ($stock->getIsNewRecord()) {
            $stock->setAttribute($this->stockQtyAttribute, 0);
        }
        return $stock;
    }

    #endregion
}
