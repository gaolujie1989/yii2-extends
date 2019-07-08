<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


use yii\db\BaseActiveRecord;

interface StockManagerInterface
{
    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function inbound(int $itemId, int $locationId, int $qty, array $extraData = []): bool;

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function outbound(int $itemId, int $locationId, int $qty, array $extraData = []): bool;

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
    public function transfer(int $itemId, int $fromLocationId, int $toLocationId, int $qty, array $extraData = []): bool;

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @throws \Throwable
     * @inheritdoc
     */
    public function correct(int $itemId, int $locationId, int $qty, array $extraData = []): bool;

    /**
     * @param int $itemId
     * @param int $locationId
     * @inheritdoc
     */
    public function calculateStock(int $itemId, int $locationId): void;

    /**
     * @param int $itemId
     * @param int $locationId
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function getStock(int $itemId, int $locationId);
}
