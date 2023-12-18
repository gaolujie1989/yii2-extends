<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\CalculatedPrice;
use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingTableQuery;
use lujie\charging\models\ShippingZone;

/**
 * Class ShippingPriceCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableCalculator extends BaseChargeCalculator
{
    /**
     * @var ?int
     */
    public $defaultOwnerId = 0;

    /**
     * @var ?string
     */
    public $defaultCarrier = 'DEFAULT';

    /**
     * @param BaseChargeItem|ShippingItem $chargeItem
     * @return CalculatedPrice|null
     * @inheritdoc
     */
    protected function calculateInternal(BaseChargeItem $chargeItem): ?CalculatedPrice
    {
        $shippingTablePrice = $this->getShippingTable($chargeItem);
        if ($shippingTablePrice === null) {
            return CalculatedPrice::createWithFailed('ShippingTable not found');
        }
        return CalculatedPrice::create($shippingTablePrice->price_cent, $shippingTablePrice->currency, $shippingTablePrice);
    }

    /**
     * @param ShippingItem $shippingItem
     * @return ShippingTable|null
     * @inheritdoc
     */
    protected function getShippingTable(ShippingItem $shippingItem): ?ShippingTable
    {
        $query = $this->getShippingTableQuery($shippingItem);
        $query->orderByPrice();
        $shippingOwnerId = $shippingItem->additional['ownerId'] ?? $shippingItem->additional['owner_id'] ?? 0;

        $carriers = [$shippingItem->carrier];
        if ($this->defaultCarrier !== null && $this->defaultCarrier !== $shippingItem->carrier) {
            $carriers[] = $this->defaultCarrier;
        }
        $ownerIds = [$shippingOwnerId];
        if ($this->defaultOwnerId !== null && $this->defaultOwnerId !== $shippingOwnerId) {
            $ownerIds[] = $this->defaultOwnerId;
        }
        foreach ($ownerIds as $ownerId) {
            $carrierZones = $this->getCarrierZones($shippingItem, $carriers, $ownerId);
            foreach ($carriers as $carrier) {
                $shippingZone = $carrierZones[$carrier] ?? '';
                $shippingTable = (clone $query)->carrier($carrier)->ownerId($ownerId)->zone($shippingZone)->one();
                if ($shippingTable !== null) {
                    return $shippingTable;
                }
            }
        }
        return null;
    }

    /**
     * @param ShippingItem $shippingItem
     * @return ShippingTableQuery
     * @inheritdoc
     */
    protected function getShippingTableQuery(ShippingItem $shippingItem): ShippingTableQuery
    {
        return ShippingTable::find()
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->activeAt($shippingItem->shippedAt ?: time())
            ->weightGLimit($shippingItem->weightG)
            ->sizeMMLimit($shippingItem->lengthMM, $shippingItem->widthMM, $shippingItem->heightMM);
    }

    /**
     * @param ShippingItem $shippingItem
     * @param array $carriers
     * @param int $ownerId
     * @return array
     * @inheritdoc
     */
    public function getCarrierZones(ShippingItem $shippingItem, array $carriers = [], int $ownerId = 0): array
    {
        return ShippingZone::find()
            ->ownerId($ownerId)
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->postalCode($shippingItem->postalCode)
            ->getCarrierZones($carriers);
    }

    /**
     * @param ShippingItem $shippingItem
     * @param array $carriers
     * @param int $ownerId
     * @return array
     * @inheritdoc
     */
    public function getShippingPrices(ShippingItem $shippingItem, array $carriers = [], int $ownerId = 0): array
    {
        $carrierZones = $this->getCarrierZones($shippingItem, $carriers, $ownerId);
        $query = $this->getShippingTableQuery($shippingItem);
        return $query->carrierZones($carrierZones)->getCarrierPrices($carriers);
    }
}
