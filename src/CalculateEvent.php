<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ChargePrice;
use yii\base\Event;
use yii\db\BaseActiveRecord;

/**
 * Class CalculateEvent
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CalculateEvent extends Event
{
    /**
     * default is false, if set true, skip calculate
     * @var bool
     */
    public $calculated = false;

    /**
     * @var BaseActiveRecord
     */
    public $model;

    /**
     * @var ChargePrice
     */
    public $chargePrice;
}
