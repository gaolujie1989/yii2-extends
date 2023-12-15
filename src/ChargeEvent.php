<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use yii\base\Event;
use yii\db\BaseActiveRecord;

/**
 * Class ChargeEvent
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeEvent extends Event
{
    /**
     * default is false, if set true, skip calculate
     * @var bool
     */
    public $calculated = false;

    /**
     * @var string
     */
    public $modelType;

    /**
     * @var array
     */
    public $chargeTypes = [];

    /**
     * @var BaseActiveRecord
     */
    public $model;

    /**
     * @var CalculatedPrice[]
     */
    public $calculatedPrices = [];
}
