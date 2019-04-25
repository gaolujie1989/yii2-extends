<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/12
 * Time: 14:51
 */

namespace lujie\stocking;


use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;

/**
 * Class StockMovementEvent
 * @package lujie\stocking
 */
class StockMovementEvent extends ModelEvent
{
    /**
     * @var BaseActiveRecord
     */
    public $stock;

    /**
     * @var BaseActiveRecord
     */
    public $stockMovement;

    public $itemId;

    public $locationId;

    public $qty;

    public $reason;

    public $extraData;
}
