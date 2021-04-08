<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Query;
use yii\db\QueryInterface;

/**
 * Class ActiveRecordStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordStockManager extends BaseStockManager
{
    /**
     * @var string|BaseActiveRecord
     */
    public $stockClass;
    /**
     * @var string|BaseActiveRecord
     */
    public $movementClass;

    /**
     * @param int $itemId
     * @param int $locationId
     * @return BaseActiveRecord
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId): BaseActiveRecord
    {
        $stock = parent::getStock($itemId, $locationId);
        if (empty($stock)) {
            $stock = new $this->stockClass();
            $stock->setAttributes([
                $this->itemIdAttribute => $itemId,
                $this->locationIdAttribute => $locationId,
            ]);
        }
        return $stock;
    }

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
     * @return QueryInterface
     * @inheritdoc
     */
    protected function stockQuery(): QueryInterface
    {
        return $this->stockClass::find();
    }

    /**
     * @return QueryInterface
     * @inheritdoc
     */
    protected function movementQuery(): QueryInterface
    {
        return $this->movementClass::find();
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return BaseActiveRecord
     * @throws \Exception
     * @inheritdoc
     */
    protected function createStockMovement(int $itemId, int $locationId, int $qty, string $reason, array $data = []): BaseActiveRecord
    {
        $movementData = array_merge($data, $this->getMovementData($itemId, $locationId, $qty, $reason));
        /** @var BaseActiveRecord $stockMovement */
        $stockMovement = new $this->movementClass();
        $stockMovement->setAttributes($movementData);
        $stockMovement->save(false);
        return $stockMovement;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $moveQty
     * @return bool
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function updateStockQty(int $itemId, int $locationId, int $moveQty): bool
    {
        $counters = [$this->stockQtyAttribute => $moveQty];
        $stock = $this->getStock($itemId, $locationId);
        if ($stock->getIsNewRecord()) {
            $stock->setAttributes($counters);
            return $stock->save(false);
        }

        if ($moveQty < 0) {
            $condition = ['>=', $this->stockQtyAttribute, -$moveQty];
            $condition = ['AND', $stock->getOldPrimaryKey(true), $condition];
            $n = $this->stockClass::updateAllCounters($counters, $condition);
            return $n > 0;
        }

        return $stock->updateCounters($counters);
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
}
