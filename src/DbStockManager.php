<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\db\Connection;
use yii\db\Expression;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class DbStockManager
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbStockManager extends BaseStockManager
{
    /**
     * @var Connection
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $stockTable;

    /**
     * @var string
     */
    public $stockMovementTable;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->db = Instance::ensure($this->db);
    }

    #region Abstract functions implements

    /**
     * @return mixed|Connection
     * @inheritdoc
     */
    protected function getDb()
    {
        return $this->db;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return array
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    protected function createStockMovement(int $itemId, int $locationId, int $qty, string $reason, array $data = []): array
    {
        $columns = $this->getDb()->getTableSchema($this->stockMovementTable)->columns;
        $data = array_intersect_key($data, $columns);

        $movementData = array_merge($data, [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->movedQtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ]);
        $result = $this->getDb()->getSchema()->insert($this->stockMovementTable, $movementData);
        return array_merge($movementData, $result);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $moveQty
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function updateStockQty(int $itemId, int $locationId, int $moveQty): bool
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $stock = $this->getStock($itemId, $locationId);
        $command = $this->getDb()->createCommand();
        if ($stock) {
            $update = [$this->stockQtyAttribute => new Expression("{$this->stockQtyAttribute} + {$moveQty}")];
            $execute = $command->update($this->stockTable, $update, $condition)->execute();
        } else {
            $insert = array_merge($condition, [$this->stockQtyAttribute => $moveQty]);
            $execute = $command->insert($this->stockTable, $insert)->execute();
        }
        return (bool)$execute;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param array $data
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function updateStock(int $itemId, int $locationId, array $data): bool
    {
        $columns = $this->getDb()->getTableSchema($this->stockTable)->columns;
        $data = array_intersect_key($data, $columns);

        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $stock = $this->getStock($itemId, $locationId);
        $command = $this->getDb()->createCommand();
        if ($stock) {
            $execute = $command->update($this->stockTable, $data, $condition)->execute();
        } else {
            $execute = $command->insert($this->stockTable, array_merge($condition, $data))->execute();
        }
        return (bool)$execute;
    }

    #endregion

    #region Interface implements

    #endregion


    /**
     * @param int $itemId
     * @param int $locationId
     * @return int
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): int
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $query = (new Query())->from($this->stockMovementTable)->andWhere($condition);
        $stockQty = $query->sum($this->movedQtyAttribute);

        $stock = $this->getStock($itemId, $locationId);
        $update = [$this->stockQtyAttribute => $stockQty];
        $command = $this->getDb()->createCommand();
        if ($stock) {
            $command->update($this->stockTable, $update, $condition)->execute();
        } else {
            $command->insert($this->stockTable, array_merge($condition, $update))->execute();
        }
        return $stockQty;
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @return array
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId): ?array
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];

        $query = (new Query())->from($this->stockTable)->andWhere($condition);
        return $query->one() ?: null;
    }
}
