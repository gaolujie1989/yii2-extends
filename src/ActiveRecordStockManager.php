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
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): void
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        /** @var Query $query */
        $query = $this->stockMovementClass::find()->andWhere($condition);
        $stockQty = $query->sum($this->moveQtyAttribute);

        $stock = $this->getStock($itemId, $locationId);
        $stock->setAttribute($this->stockQtyAttribute, $stockQty);
        $stock->save(false);
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


    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return BaseActiveRecord
     * @inheritdoc
     */
    protected function createStockMovement($itemId, $locationId, int $qty, $reason, $extraData = []): BaseActiveRecord
    {
        $data = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->moveQtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ];
        /** @var BaseActiveRecord $stockMovement */
        $stockMovement = new $this->stockMovementClass();
        $stockMovement->setAttributes($extraData);
        $stockMovement->setAttributes($data);
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
        if ($stock->getIsNewRecord()) {
            $stock->setAttributes($data);
            return $stock->save(false);
        }
        $stock->updateAttributes($data);
        return true;
    }
}
