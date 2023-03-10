<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\common;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Item
 * @package lujie\fulfillment\common
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Item extends Model
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
     * @var array
     */
    public $imageUrls = [];

    /**
     * @var string
     */
    public $salesUrl;

    /**
     * @var ItemBarcode[]
     */
    public $itemBarcodes;

    /**
     * @var ItemValue[]
     */
    public $itemValues;

    /**
     * @var array
     */
    public $additional = [];

    /**
     * @param string $name
     * @return string|null
     * @inheritdoc
     */
    public function getBarcode(string $name): ?string
    {
        if (!ArrayHelper::isAssociative($this->itemBarcodes)) {
            $this->itemBarcodes = ArrayHelper::index($this->itemBarcodes, 'name');
        }
        return isset($this->itemBarcodes[$name]) ? $this->itemBarcodes[$name]->code : null;
    }
}
