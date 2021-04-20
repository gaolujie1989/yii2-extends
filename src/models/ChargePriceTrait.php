<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\models;

/**
 * Trait ChargePriceTrait
 * @package lujie\charging\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ChargePriceTrait
{
    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->calculateTotal();
        $this->setChargePriceStatus();
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    protected function calculateTotal(): void
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
}