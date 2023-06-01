<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ChargeTable;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableCalculator extends BaseObject implements ChargeCalculatorInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $chargeableItemLoader = 'chargeableItemLoader';

    /**
     * @var bool
     */
    public $cheapFirst = true;

    /**
     * @var bool
     */
    public $roundUp = true;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeableItemLoader = Instance::ensure($this->chargeableItemLoader, DataLoaderInterface::class);
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
        $chargePrice->note = '';
        $chargePrice->error = '';

        /** @var ChargeableItem[] $chargeableItems */
        $chargeableItems = $this->chargeableItemLoader->get($model);
        if (empty($chargeableItems)) {
            $chargePrice->error = 'Empty ChargeableItem';
            return $chargePrice;
        }

        if (!is_array($chargeableItems)) {
            $chargeableItems = [$chargeableItems];
        }

        $internalChargePrices = [];
        foreach ($chargeableItems as $chargeableItem) {
            $internalChargePrice = new ChargePrice();
            $internalChargePrice->custom_type =  is_array($chargeableItem->customType)
                ? implode(',', $chargeableItem->customType)
                : $chargeableItem->customType;
            $internalChargePrice->setAttributes($chargeableItem->additional);
            $this->calculateInternal($chargeableItem, $internalChargePrice);
            $internalChargePrices[] = $internalChargePrice;
        }

        $this->mergeChargePrices($internalChargePrices, $chargePrice);
        return $chargePrice;
    }

    /**
     * @param array $internalChargePrices
     * @param ChargePrice $chargePrice
     * @inheritdoc
     */
    protected function mergeChargePrices(array $internalChargePrices, ChargePrice $chargePrice): void
    {
        $chargePrice->price_table_id = ArrayHelper::getColumn($internalChargePrices, 'price_table_id')[0];
        $chargePrice->price_cent = array_sum(ArrayHelper::getColumn($internalChargePrices, 'price_cent'));
        $chargePrice->currency = ArrayHelper::getColumn($internalChargePrices, 'currency')[0];
        $notes = ArrayHelper::getColumn($internalChargePrices, 'note');
        $totalNote = strtr(' = {price} {currency}', [
            '{price}' => $chargePrice->price_cent / 100,
            '{currency}' => $chargePrice->currency,
        ]);
        $chargePrice->note = implode(' + ', $notes) . $totalNote;
        $chargePrice->additional = array_merge(...ArrayHelper::getColumn($internalChargePrices, 'additional'));
    }

    /**
     * @param ChargeableItem $chargeableItem
     * @param ChargePrice $chargePrice
     * @inheritdoc
     */
    protected function calculateInternal(ChargeableItem $chargeableItem, ChargePrice $chargePrice): void
    {
        $chargeTablePrice = $this->getChargeTablePrice($chargeableItem, $chargePrice->charge_type);
        if ($chargeTablePrice === null) {
            $chargePrice->price_table_id = 0;
            $chargePrice->price_cent = 0;
            $chargePrice->error = 'Null ChargeTablePrice';
            return;
        }
        $chargePrice->price_table_id = $chargeTablePrice->charge_table_id;
        $chargePrice->setAttributes($chargeTablePrice->additional);
        if ($chargeableItem->basePriceCurrency) {
            $chargePrice->price_cent =  round($chargeableItem->basePriceCent * $chargeTablePrice->percent / 100);
            $chargePrice->currency = $chargeableItem->basePriceCurrency;
            $chargePrice->note = strtr("{basePrice} x {percent}%", [
                '{basePrice}' => $chargeableItem->basePriceCent / 100,
                '{percent}' => $chargeTablePrice->percent,
            ]);
            $chargePrice->additional = array_merge($chargePrice->additional ?: [], [
                'base_price_cent' => $chargeableItem->basePriceCent,
                'percent' => $chargeTablePrice->percent,
            ]);
        } else {
            $chargePrice->price_cent = $chargeTablePrice->price_cent;
            $chargePrice->currency = $chargeTablePrice->currency;
            $chargePrice->note = $chargeTablePrice->price_cent / 100;
            $chargePrice->additional = array_merge($chargePrice->additional ?: [], [
                'limit_value' => $chargeableItem->limitValue,
            ]);
            if ($chargeableItem->limitValue > $chargeTablePrice->max_limit) {
                $overLimit = ($chargeableItem->limitValue - $chargeTablePrice->max_limit) / $chargeTablePrice->per_limit;
                if ($this->roundUp) {
                    $overLimit = (int)ceil($overLimit);
                }
                $chargePrice->price_cent += $overLimit * $chargeTablePrice->over_limit_price_cent;
                $chargePrice->note = strtr("({limitPrice} + {overLimitPrice} * {overLimitValue})", [
                    '{limitPrice}' => $chargeTablePrice->price_cent / 100,
                    '{overLimitPrice}' => $chargeTablePrice->over_limit_price_cent / 100,
                    '{overLimitValue}' => $overLimit,
                ]);
            }
        }
    }

    /**
     * @param ChargeableItem $chargeableItem
     * @param string $chargeType
     * @return ChargeTable|null
     * @inheritdoc
     */
    protected function getChargeTablePrice(ChargeableItem $chargeableItem, string $chargeType): ?ChargeTable
    {
        $query = ChargeTable::find()
            ->activeAt($chargeableItem->chargedAt ?: time())
            ->chargeType($chargeType)
            ->customType($chargeableItem->customType)
            ->limitValue($chargeableItem->limitValue)
            ->orderByPrice($this->cheapFirst ? SORT_ASC : SORT_DESC);
        $ownerId = $chargeableItem->additional['owner_id'] ?? 0;
        $chargeTable = (clone $query)->ownerId($ownerId)->one();
        if ($chargeTable === null && $ownerId > 0) {
            $chargeTable = (clone $query)->ownerId(0)->one();
        }
        return $chargeTable;
    }
}
