<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

/**
 * Class ShippingPackage
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingItem extends BaseChargeItem
{
    /**
     * @var string
     */
    public $carrier;

    /**
     * @var string
     */
    public $departure = 'DE';

    /**
     * @var string
     */
    public $destination;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var int
     */
    public $shippedAt;

    /**
     * @var int
     */
    public $weightG;

    /**
     * @var int
     */
    public $lengthMM;

    /**
     * @var int
     */
    public $widthMM;

    /**
     * @var int
     */
    public $heightMM;

    /**
     * @param int $weightG
     * @param int $lengthMM
     * @param int $widthMM
     * @param int $heightMM
     * @param string $carrier
     * @param string $postalCode
     * @param string $destination
     * @param string $departure
     * @param int|null $shippedAt
     * @return static
     * @inheritdoc
     */
    public static function create(
        int $weightG,
        int $lengthMM,
        int $widthMM,
        int $heightMM,
        string $carrier,
        string $postalCode,
        string $destination,
        string $departure,
        int $shippedAt = null,
    ): static
    {
        $shippingItem = new ShippingItem();
        $shippingItem->weightG = $weightG;
        $shippingItem->lengthMM = $lengthMM;
        $shippingItem->widthMM = $widthMM;
        $shippingItem->heightMM = $heightMM;
        $shippingItem->carrier = $carrier;
        $shippingItem->postalCode = $postalCode;
        $shippingItem->destination = $destination;
        $shippingItem->departure = $departure;
        $shippingItem->shippedAt = $shippedAt ?: time();
        return $shippingItem;
    }
}
