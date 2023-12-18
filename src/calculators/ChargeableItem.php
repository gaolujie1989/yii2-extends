<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

/**
 * Class ChargeLine
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeableItem extends BaseChargeItem
{
    /**
     * @var string
     */
    public $customType;

    /**
     * @var int
     */
    public $chargedAt;

    /**
     * @var int
     */
    public $limitValue;

    /**
     * @var int
     */
    public $basePriceCent;

    /**
     * @var int
     */
    public $basePriceCurrency;

    /**
     * @param string $
     * @param int $limitValue
     * @param string $customType
     * @param int|null $chargedAt
     * @return static
     * @inheritdoc
     */
    public static function create(
        string $itemKey,
        int $limitValue,
        string $customType = '',
        ?int $chargedAt = null,
    ): static
    {
        $item = new static();
        $item->itemKey = $itemKey;
        $item->limitValue = $limitValue;
        $item->customType = $customType;
        $item->chargedAt = $chargedAt ?: time();
        return $item;
    }

    /**
     * @param string $
     * @param int $basePriceCent
     * @param string $basePriceCurrency
     * @param string $customType
     * @param int|null $chargedAt
     * @return static
     * @inheritdoc
     */
    public static function createWithPrice(
        string $itemKey,
        int $basePriceCent,
        string $basePriceCurrency,
        string $customType = '',
        ?int $chargedAt = null,
    ): static
    {
        $item = new static();
        $item->itemKey = $itemKey;
        $item->basePriceCent = $basePriceCent;
        $item->basePriceCurrency = $basePriceCurrency;
        $item->customType = $customType;
        $item->chargedAt = $chargedAt ?: time();
        return $item;
    }
}
