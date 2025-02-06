<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\CalculatedPrice;
use lujie\charging\models\ChargeTable;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableCalculator extends BaseChargeCalculator
{
    /**
     * @var bool
     */
    public $cheapFirst = true;

    /**
     * @var bool
     */
    public $roundUp = true;

    /**
     * @param BaseChargeItem|ChargeableItem $chargeItem
     * @return CalculatedPrice|null
     * @inheritdoc
     */
    protected function calculateInternal(BaseChargeItem $chargeItem): ?CalculatedPrice
    {
        $chargeTablePrice = $this->getChargeTable($chargeItem);
        if ($chargeTablePrice === null) {
            return CalculatedPrice::createWithFailed('ChargeTable not found');
        }

        if ($chargeItem->basePriceCurrency) {
            $priceCent =  round($chargeItem->basePriceCent * $chargeTablePrice->percent / 100);
            $currency = $chargeItem->basePriceCurrency;
            $note = strtr("{basePrice} x {percent}%", [
                '{basePrice}' => $chargeItem->basePriceCent / 100,
                '{percent}' => $chargeTablePrice->percent,
            ]);
        } else {
            $priceCent = $chargeTablePrice->price_cent;
            $currency = $chargeTablePrice->currency;
            $note = $chargeTablePrice->price_cent / 100;
            if ($chargeItem->limitValue > $chargeTablePrice->max_limit) {
                $overLimit = ($chargeItem->limitValue - $chargeTablePrice->max_limit) / $chargeTablePrice->per_limit;
                if ($this->roundUp) {
                    $overLimit = (int)ceil($overLimit);
                }
                $priceCent += $overLimit * $chargeTablePrice->over_limit_price_cent;
                $note = strtr("({limitPrice} + {overLimitPrice} * {overLimitValue})", [
                    '{limitPrice}' => $chargeTablePrice->price_cent / 100,
                    '{overLimitPrice}' => $chargeTablePrice->over_limit_price_cent / 100,
                    '{overLimitValue}' => $overLimit,
                ]);
            }
        }

        return CalculatedPrice::create($priceCent, $currency, $chargeTablePrice, $note);
    }

    /**
     * @param ChargeableItem $chargeableItem
     * @return ChargeTable|null
     * @inheritdoc
     */
    protected function getChargeTable(ChargeableItem $chargeableItem): ?ChargeTable
    {
        $query = ChargeTable::find()
            ->activeAt($chargeableItem->chargedAt ?: time())
            ->chargeType($this->chargeType)
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
