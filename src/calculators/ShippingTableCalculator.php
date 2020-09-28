<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ShippingTable;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class ShippingPriceCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableCalculator extends BaseObject implements ChargeCalculatorInterface
{
    public $allowDefault = true;

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
            $chargePrice->error = 'Null ShippingTablePrice';
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
        $l2whMM = $shippingItem->lengthMM + ($shippingItem->widthMM + $shippingItem->heightMM) * 2;
        $lhMM = $shippingItem->lengthMM + $shippingItem->heightMM;
        $volumeMM3 = $shippingItem->lengthMM * $shippingItem->widthMM * $shippingItem->heightMM;
        $query = ShippingTable::find()
            ->activeAt($shippingItem->shippedAt ?: time())
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->carrier($shippingItem->carrier)
            ->weightGLimit($shippingItem->weightG)
            ->lengthMMLimit($shippingItem->lengthMM)
            ->widthMMLimit($shippingItem->widthMM)
            ->heightMMLimit($shippingItem->heightMM)
            ->minLengthMMLimit($shippingItem->lengthMM)
            ->minWidthMMLimit($shippingItem->widthMM)
            ->minHeightMMLimit($shippingItem->heightMM)
            ->l2whMMLimit($l2whMM)
            ->lhMMLimit($lhMM)
            ->volumeMM3Limit($volumeMM3)
            ->orderByPrice(SORT_ASC);
        $ownerId = $shippingItem->additional['owner_id'] ?? 0;
        $shippingTable = (clone $query)->ownerId($ownerId)->one();
        if ($shippingTable === null && $ownerId > 0 && $this->allowDefault) {
            $shippingTable = (clone $query)->ownerId(0)->one();
        }
        return $shippingTable;
    }
}
