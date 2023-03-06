<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock;

use yii\base\Exception;

/**
 * Class MovementException
 * @package lujie\balance
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MovementException extends Exception
{
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
    public $movedQty;
}