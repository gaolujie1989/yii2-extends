<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingZone;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\TemplateHelper;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ShippingPriceCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableCalculator extends BaseObject implements ChargeCalculatorInterface
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
     * @var DataLoaderInterface
     */
    public $shippingItemLoader = 'shippingItemLoader';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->shippingItemLoader = Instance::ensure($this->shippingItemLoader, DataLoaderInterface::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param ChargePrice $chargePrice
     * @return ChargePrice
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        $chargePrice->price_table_id = 0;
        $chargePrice->price_cent = 0;
        $chargePrice->currency = '';
        $chargePrice->error = '';

        /** @var ?ShippingItem $shippingItem */
        $shippingItem = $this->shippingItemLoader->get($model);
        if ($shippingItem === null) {
            $chargePrice->error = 'Null ShippingItem';
            return $chargePrice;
        }

        $chargePrice->custom_type = $shippingItem->carrier;
        $chargePrice->setAttributes($shippingItem->additional);

        $shippingTablePrice = $this->getShippingTablePrice($shippingItem);
        if ($shippingTablePrice === null) {
            $chargePrice->error = TemplateHelper::render('Null ShippingTablePrice of Item[{weightG}G][{lengthMM}x{widthMM}x{heightMM}MM]', $shippingItem);
            return $chargePrice;
        }

        $chargePrice->price_table_id = $shippingTablePrice->shipping_table_id;
        $chargePrice->price_cent = $shippingTablePrice->price_cent;
        $chargePrice->currency = $shippingTablePrice->currency;
        return $chargePrice;
    }

    /**
     * @param ShippingItem $shippingItem
     * @return ShippingTable|null
     * @inheritdoc
     */
    protected function getShippingTablePrice(ShippingItem $shippingItem): ?ShippingTable
    {
        $query = ShippingTable::find()
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->activeAt($shippingItem->shippedAt ?: time())
            ->weightGLimit($shippingItem->weightG)
            ->sizeMMLimit($shippingItem->lengthMM, $shippingItem->widthMM, $shippingItem->heightMM)
            ->warehouseId($shippingItem->warehouseId)
            ->orderByPrice(SORT_ASC);
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
            $shippingZones = $this->getShippingZone($shippingItem, $carriers, $ownerId);
            foreach ($carriers as $carrier) {
                $shippingZone = $shippingZones[$carrier] ?? '';
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
     * @param array $carriers
     * @param int $ownerId
     * @return array
     * @inheritdoc
     */
    protected function getShippingZone(ShippingItem $shippingItem, array $carriers = [], $ownerId = 0): array
    {
        return ShippingZone::find()
            ->ownerId($ownerId)
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->postalCode($shippingItem->postalCode)
            ->getCarrierZones($carriers);
    }
}
