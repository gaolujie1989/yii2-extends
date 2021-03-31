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
     * for matching limit
     * @var int
     */
    public $limitValue;

    /**
     * @var int
     */
    public $chargedAt;

    /**
     * @var array
     */
    public $additional = [];
}
