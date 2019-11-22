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
     * @var string
     */
    public $customType;

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
     * @var int
     */
    public $qty;

    /**
     * @var int
     */
    public $ownerId;

    /**
     * @var int
     */
    public $parentId;
}
