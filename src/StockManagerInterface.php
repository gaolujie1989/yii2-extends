<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;


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
    public function inbound($itemId, $locationId, int $qty, $extraData = []);

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function outbound($itemId, $locationId, int $qty, $extraData = []);

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
    public function transfer($itemId, $fromLocationId, $toLocationId, int $qty, $extraData = []);

    /**
     * @param $itemId
     * @param $locationId
     * @param int $qty
     * @param array $extraData
     * @throws \Throwable
     * @inheritdoc
     */
    public function correct($itemId, $locationId, int $qty, $extraData = []);

    /**
     * @param $itemId
     * @param $locationId
     * @return bool
     * @inheritdoc
     */
    public function calculateStock($itemId, $locationId);

    /**
     * @param $itemId
     * @param $locationId
     * @return mixed
     * @inheritdoc
     */
    public function getStock($itemId, $locationId);
}
