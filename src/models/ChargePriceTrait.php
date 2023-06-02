<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\models;

use yii\helpers\ArrayHelper;

/**
 * Trait ChargePriceTrait
 * @package lujie\charging\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ChargePriceTrait
{
    public function resetPrice(): void
    {
        $this->price_table_id = 0;
        $this->price_cent = 0;
        $this->currency = '';
        $this->note = '';
        $this->error = '';
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->calculateTotal();
        $this->appendTotalNote();
        $this->setChargePriceStatus();
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function calculateTotal(): void
    {
        if (empty($this->discount_cent)) {
            $this->discount_cent = 0;
        }
        if (empty($this->surcharge_cent)) {
            $this->surcharge_cent = 0;
        }
        $this->subtotal_cent = $this->price_cent * $this->qty;
        $this->grand_total_cent = $this->subtotal_cent - $this->discount_cent + $this->surcharge_cent;
    }

    /**
     * @inheritdoc
     */
    public function appendTotalNote(): void
    {
        if (empty($this->note)) {
            return;
        }
        $appendNote = '';
        if ($this->qty > 1) {
            $appendNote .= ' x ' . $this->qty;
        }
        if ($this->discount_cent !== 0) {
            $appendNote .= ' - ' . ($this->discount_cent / 100);
        }
        if ($this->surcharge_cent !== 0) {
            $appendNote .= ' + ' . ($this->subtotal_cent / 100);
        }

        if ($appendNote) {
            $this->note .= $appendNote;
            $this->note .= ' = ' . $this->grand_total_cent / 100 . ' ' . $this->currency;
        }
    }

    /**
     * @inheritdoc
     */
    protected function setChargePriceStatus(): void
    {
        if (empty($this->price_table_id)) {
            $this->status = self::STATUS_FAILED;
            $this->price_table_id = 0;
            $this->price_cent = 0;
            $this->currency = '';
        } elseif ($this->status === self::STATUS_FAILED) {
            $this->status = self::STATUS_ESTIMATE;
        }
    }

    /**
     * @param int|mixed $priceCent
     * @inheritdoc
     */
    public function setDiscountPriceCent($priceCent): void
    {
        if (is_numeric($priceCent)) {
            $this->discount_cent = $priceCent * $this->qty;
        } else {
            $this->discount_cent = 0;
        }
    }

    /**
     * @param int|mixed $percent
     * @inheritdoc
     */
    public function setDiscountPercent($percent): void
    {
        if (is_numeric($percent)) {
            $this->discount_cent = (int)round($this->price_cent * $this->qty * $percent / 100, 0, PHP_ROUND_HALF_DOWN);
        } else {
            $this->discount_cent = 0;
        }
    }

    /**
     * @param ChargePrice[] $chargePrices
     * @param ChargePrice $xxx
     * @inheritdoc
     */
    public function mergeChargePrices(array $chargePrices): void
    {
        if (empty($chargePrices)) {
            return;
        }
        $chargePrice = reset($chargePrices);
        $this->setAttribute($chargePrice->getDirtyAttributes(), false);
        if (count($chargePrices) > 1) {
            $notes = [];
            foreach ($chargePrices as $chargePrice) {
                $chargePrice->calculateTotal();
                $notes[] = $chargePrice->note . ($chargePrice->qty > 1 ? ' x ' . $chargePrice->qty : '');
            }
            $this->subtotal_cent = array_sum(ArrayHelper::getColumn($chargePrices, 'subtotal_cent'));
            $this->price_cent = $this->subtotal_cent;
            $this->qty = 1;
            $this->discount_cent = array_sum(ArrayHelper::getColumn($chargePrices, 'discount_cent'));
            $this->surcharge_cent = array_sum(ArrayHelper::getColumn($chargePrices, 'surcharge_cent'));
            $this->grand_total_cent = array_sum(ArrayHelper::getColumn($chargePrices, 'grand_total_cent'));
            $this->note = implode(' + ', $notes);
            $this->additional = array_merge(...ArrayHelper::getColumn($chargePrices, 'additional'));
        }
    }
}
