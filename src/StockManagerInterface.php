<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

/**
 * Interface StockManagerInterface
 * @package lujie\stock
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface StockManagerInterface
{
    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return mixed|null StockMovement
     * @inheritdoc
     */
    public function inbound(int $itemId, int $locationId, int $qty, array $data = []);

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return mixed|null StockMovement
     * @inheritdoc
     */
    public function outbound(int $itemId, int $locationId, int $qty, array $data = []);

    /**
     * @param int $itemId
     * @param int $fromLocationId
     * @param int $toLocationId
     * @param int $qty
     * @param array $data
     * @return array|null StockMovements
     * @inheritdoc
     */
    public function transfer(int $itemId, int $fromLocationId, int $toLocationId, int $qty, array $data = []): ?array;

    /**
     * @param int $itemId
     * @param int $locationId
     * @param int $qty
     * @param array $data
     * @return mixed|null StockMovement
     * @inheritdoc
     */
    public function correct(int $itemId, int $locationId, int $qty, array $data = []);

    /**
     * @param int $itemId
     * @param int $locationId
     * @return int|null
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): ?int;

    /**
     * @param int $itemId
     * @param int $locationId
     * @return mixed|null Stock
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId);
}
