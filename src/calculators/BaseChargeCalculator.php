<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingTableQuery;
use lujie\charging\models\ShippingZone;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\TemplateHelper;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseChargeCalculator extends BaseObject implements ChargeCalculatorInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $chargeItemLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeItemLoader = Instance::ensure($this->chargeItemLoader, DataLoaderInterface::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param ChargePrice $chargePrice
     * @return ChargePrice
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        $chargePrice->resetPrice();

        /** @var BaseChargeItem[] $chargeItems */
        $chargeItems = $this->chargeItemLoader->get($model);
        if (empty($chargeItems)) {
            $chargePrice->error = 'Empty Charge Items';
            return $chargePrice;
        }

        if (!is_array($chargeItems)) {
            $chargeItems = [$chargeItems];
        }

        $internalChargePrices = [];
        foreach ($chargeItems as $chargeItem) {
            $internalChargePrice = new ChargePrice();
            $internalChargePrice->qty = $chargeItem->qty ?: 0;
            $internalChargePrice->setAttributes($chargeItem->additional);

            $this->calculateInternal($chargeItem, $internalChargePrice);
            if ($internalChargePrice->error) {
                $chargePrice->error = $internalChargePrice->error;
                return $chargePrice;
            }
            $internalChargePrices[] = $internalChargePrice;
        }

        $this->mergeChargePrices($chargePrice, $internalChargePrices);
        return $chargePrice;
    }

    /**
     * @param BaseChargeItem $shippingItem
     * @param ChargePrice $chargePrice
     * @inheritdoc
     */
    abstract protected function calculateInternal(BaseChargeItem $shippingItem, ChargePrice $chargePrice): void;

    /**
     * @param ChargePrice $chargePrice
     * @param array $mergeChargePrices
     * @inheritdoc
     */
    protected function mergeChargePrices(ChargePrice $chargePrice, array $mergeChargePrices): void
    {
        if (empty($mergeChargePrices)) {
            return;
        }
        $mergeChargePrice = reset($mergeChargePrices);
        $chargePrice->setAttribute($mergeChargePrice->getDirtyAttributes(), false);
        if (count($mergeChargePrices) > 1) {
            $notes = [];
            foreach ($mergeChargePrices as $mergeChargePrice) {
                $mergeChargePrice->calculateTotal();
                $notes[] = $mergeChargePrice->note . ($mergeChargePrice->qty > 1 ? ' x ' . $mergeChargePrice->qty : '');
            }
            $chargePrice->subtotal_cent = array_sum(ArrayHelper::getColumn($mergeChargePrices, 'subtotal_cent'));
            $chargePrice->price_cent = $chargePrice->subtotal_cent;
            $chargePrice->qty = 1;
            $chargePrice->discount_cent = array_sum(ArrayHelper::getColumn($mergeChargePrices, 'discount_cent'));
            $chargePrice->surcharge_cent = array_sum(ArrayHelper::getColumn($mergeChargePrices, 'surcharge_cent'));
            $chargePrice->grand_total_cent = array_sum(ArrayHelper::getColumn($mergeChargePrices, 'grand_total_cent'));
            $chargePrice->note = implode(' + ', $notes);
            $chargePrice->additional = array_merge(...ArrayHelper::getColumn($mergeChargePrices, 'additional'));
        }
    }
}
