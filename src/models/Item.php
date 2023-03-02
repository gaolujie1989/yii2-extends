<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\models;


use yii\base\Model;

/**
 * Class Item
 * @package lujie\extend\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Item extends Model implements ItemInterface
{
    /**
     * @var int
     */
    public $itemId;

    /**
     * @var string
     */
    public $itemNo;

    /**
     * @var string
     */
    public $itemName;

    /**
     * @var int
     */
    public $weightG;

    /**
     * @var int
     */
    public $weightNetG;

    /**
     * @var int
     */
    public $lengthMM;

    /**
     * @var int
     */
    public $widthMM;

    /**
     * @var int
     */
    public $heightMM;

    /**
     * @var ItemBarcode[]
     */
    public $itemBarcodes;

    /**
     * @var array
     */
    public $additional = [];

    #region ItemInterface

    /**
     * @return int
     */
    public function getWeightG(): int
    {
        return $this->weightG;
    }

    /**
     * @return int
     */
    public function getLengthMM(): int
    {
        return $this->lengthMM;
    }

    /**
     * @return int
     */
    public function getWidthMM(): int
    {
        return $this->widthMM;
    }

    /**
     * @return int
     */
    public function getHeightMM(): int
    {
        return $this->heightMM;
    }

    #endregion
}