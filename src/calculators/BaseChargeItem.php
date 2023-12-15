<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\charging\calculators;

use yii\base\BaseObject;

/**
 * Class BaseChargeItem
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseChargeItem extends BaseObject
{
    public $chargeKey;

    /**
     * @var ?int
     */
    public $qty;

    /**
     * @var array
     */
    public $additional = [];
}
