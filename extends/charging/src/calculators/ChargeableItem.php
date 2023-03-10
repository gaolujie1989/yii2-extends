<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use yii\base\BaseObject;

/**
 * Class ChargeLine
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeableItem extends BaseObject
{
    /**
     * @var string|array
     */
    public $customType = '';

    /**
     * @var int
     */
    public $chargedAt;

    /**
     * for matching limit
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
     * @var array
     */
    public $additional = [];
}
