<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


use yii\db\BaseActiveRecord;
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

    /**
     * @param int $itemId
     * @param int $fromLocationId
     * @param int $toLocationId
     * @param int $qty
     * @param array $extraData
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function transfer(int $itemId, int $fromLocationId, int $toLocationId, int $qty, array $extraData = []): bool
    {
        $callable = static function () use ($itemId, $fromLocationId, $toLocationId, $qty, $extraData) {
            return parent::transfer($itemId, $fromLocationId, $toLocationId, $qty, $extraData);
        };
        $db = $this->db;
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        }
        return $callable();
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    protected function moveStock(int $itemId, int $locationId, int $qty, $reason, $extraData = []): bool
    {
        $callable = static function () use ($itemId, $locationId, $qty, $reason, $extraData) {
            return parent::moveStock($itemId, $locationId, $qty, $reason, $extraData);
        };
        $db = $this->db;
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        }
        return $callable();
    }

    /**
     * @param int $itemId
     * @param int $locationId
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): void
    {
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $query = (new Query())->from($this->stockMovementTable)->andWhere($condition);
        $stockQty = $query->sum($this->moveQtyAttribute);

        $stock = $this->getStock($itemId, $locationId);
        $update = [$this->stockQtyAttribute => $stockQty];
        if ($stock) {
            $this->db->createCommand()
                ->update($this->stockTable, $update, $condition)
                ->execute();
        } else {
            $this->db->createCommand()
                ->insert($this->stockTable, array_merge($condition, $update))
                ->execute();
        }
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
        return $query->one();
    }


    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param $reason
     * @param array $extraData
     * @return array
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function createStockMovement($itemId, $locationId, int $qty, $reason, $extraData = []): array
    {
        $data = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
            $this->moveQtyAttribute => $qty,
            $this->reasonAttribute => $reason,
        ];
        $stockMovement = array_merge($extraData, $data);
        $this->db->createCommand()
            ->insert($this->stockMovementTable, $stockMovement)
            ->execute();
        return $stockMovement;
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
        if ($stock) {
            $update = [$this->stockQtyAttribute => new Expression("{$this->stockQtyAttribute} + {$moveQty}")];
            $this->db->createCommand()
                ->update($this->stockTable, $update, $condition)
                ->execute();
            return true;
        }
        $insert = array_merge($condition, [$this->stockQtyAttribute => $moveQty]);
        $this->db->createCommand()
            ->insert($this->stockTable, $insert)
            ->execute();
        return true;
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
        $condition = [
            $this->itemIdAttribute => $itemId,
            $this->locationIdAttribute => $locationId,
        ];
        $stock = $this->getStock($itemId, $locationId);
        if ($stock) {
            $this->db->createCommand()
                ->update($this->stockTable, $data, $condition)
                ->execute();
        } else {
            $this->db->createCommand()
                ->insert($this->stockTable, array_merge($condition, $data))
                ->execute();
        }
        return true;
    }
}
