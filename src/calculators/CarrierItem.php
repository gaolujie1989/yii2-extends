<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use yii\base\BaseObject;

/**
 * Class CarrierItem
 * @package ccship\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CarrierItem extends BaseObject
{
    /**
     * @var string
     */
    public $carrier;

    /**
     * @var int
     */
    public $shippedAt = 0;

    /**
     * @var array
     */
    public $trackingNumbers = [];

    /**
     * @var array
     */
    public $additional = [];
}
