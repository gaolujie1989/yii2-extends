<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/12
 * Time: 14:51
 */

namespace lujie\stock;


use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;

/**
 * Class StockMovementEvent
 * @package lujie\stock
 */
class StockMovementEvent extends ModelEvent
{
    /**
     * @var BaseActiveRecord|array
     */
    public $stockMovement;

    /**
     * @var int
     */
    public $itemId;

    /**
     * @var int
     */
    public $locationId;

    /**
     * @var int
     */
    public $stockQty;

    /**
     * @var int
     */
    public $moveQty;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var array
     */
    public $extraData;
}
