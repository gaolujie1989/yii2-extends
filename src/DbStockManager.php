<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\db\Connection;
use yii\db\Expression;
use yii\db\Query;
use yii\db\QueryInterface;
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
    public $movementTable;

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
     * @return QueryInterface
     * @inheritdoc
     */
    protected function stockQuery(): QueryInterface
    {
        return (new Query())->from($this->stockTable);
    }

    /**
     * @return QueryInterface
     * @inheritdoc
     */
    protected function movementQuery(): QueryInterface
    {
        return (new Query())->from($this->movementTable);
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param string $reason
     * @param array $data
     * @return array
     * @throws \yii\base\NotSupportedException
     * @throws \Exception
     * @inheritdoc
     */
    protected function createStockMovement(int $itemId, int $locationId, int $qty, string $reason, array $data = []): array
    {
        $movementData = array_merge($data, $this->getMovementData($itemId, $locationId, $qty, $reason));

        $columns = $this->getDb()->getTableSchema($this->movementTable)->columns;
        $movementData = array_intersect_key($movementData, $columns);

        $result = $this->getDb()->getSchema()->insert($this->movementTable, $movementData);
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
            if ($moveQty < 0) {
                $condition = ['AND', $condition, ['>=', $this->stockQtyAttribute, -$moveQty]];
            }
            $update = [$this->stockQtyAttribute => new Expression("[[$this->stockQtyAttribute]]+:bp0", ['bp0' => $moveQty])];
            $n = $command->update($this->stockTable, $update, $condition)->execute();
        } else {
            $insert = array_merge($condition, [$this->stockQtyAttribute => $moveQty]);
            $n = $command->insert($this->stockTable, $insert)->execute();
        }
        return $n > 0;
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
            $n = $command->update($this->stockTable, $data, $condition)->execute();
        } else {
            $n = $command->insert($this->stockTable, array_merge($condition, $data))->execute();
        }
        return $n > 0;
    }

    #endregion
}
