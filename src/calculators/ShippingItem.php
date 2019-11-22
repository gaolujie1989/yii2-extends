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
class ShippingItem extends ChargeableItem
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
     * @var int
     */
    public $shippedAt = 0;

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
}
