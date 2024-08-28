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
     * @var string
     */
    public $basePriceCurrency;

    /**
     * @param int $limitValue
     * @param string $customType
     * @param int|null $chargedAt
     * @param string|null $itemKey
     * @return static
     * @inheritdoc
     */
    public static function create(
        int $limitValue,
        string $customType = '',
        ?int $chargedAt = null,
        ?string $itemKey = null,
    ): static
    {
        $item = new static();
        $item->limitValue = $limitValue;
        $item->customType = $customType;
        $item->chargedAt = $chargedAt ?: time();
        $item->itemKey = $itemKey;
        return $item;
    }

    /**
     * @param int $basePriceCent
     * @param string $basePriceCurrency
     * @param string $customType
     * @param int|null $chargedAt
     * @param string|null $itemKey
     * @return static
     * @inheritdoc
     */
    public static function createWithPrice(
        int $basePriceCent,
        string $basePriceCurrency,
        int $limitValue = 0,
        string $customType = '',
        ?int $chargedAt = null,
        ?string $itemKey = null,
    ): static
    {
        $item = static::create($limitValue, $customType, $chargedAt, $itemKey);;
        $item->basePriceCent = $basePriceCent;
        $item->basePriceCurrency = $basePriceCurrency;
        return $item;
    }
}
