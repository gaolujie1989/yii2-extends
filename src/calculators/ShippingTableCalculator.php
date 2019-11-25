<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ShippingTable;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class ShippingPriceCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableCalculator extends BaseObject implements ChargeCalculatorInterface
{
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
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        /** @var ShippingItem $shippingItem */
        $shippingItem = $this->shippingItemLoader->get($model);
        $shippingTablePrice = $this->getShippingTablePrice($shippingItem);
        if ($shippingTablePrice === null) {
            throw new InvalidConfigException('No matched shipping price');
        }

        $chargePrice->custom_type = $shippingItem->carrier;
        $chargePrice->setAttributes($shippingItem->additional);

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
        $query = ShippingTable::find()
            ->ownerId($shippingItem->additional['owner_id'] ?? 0)
            ->activeAt($shippingItem->shippedAt ?: time())
            ->departure($shippingItem->departure)
            ->destination($shippingItem->destination)
            ->carrier($shippingItem->carrier)
            ->weightGLimit($shippingItem->weightG)
            ->lengthMMLimit($shippingItem->lengthMM)
            ->widthMMLimit($shippingItem->widthMM)
            ->heightMMLimit($shippingItem->heightMM)
            ->l2whMMLimit($l2whMM)
            ->lhMMLimit($lhMM)
            ->orderByPrice(SORT_ASC);
        return $query->one();
    }
}
