<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\calculators\BaseChargeItem;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class CalculateEvent
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CalculatedPrice extends BaseObject
{
    /**
     * @var BaseActiveRecord
     */
    public $chargeModel;

    /**
     * @var BaseChargeItem
     */
    public $chargeItem;

    /**
     * @var string
     */
    public $modelType;

    /**
     * @var string
     */
    public $chargeType;

    /**
     * @var BaseActiveRecord
     */
    public $priceTable;

    /**
     * @var int
     */
    public $priceCent;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var bool
     */
    public $isSuccess;

    /**
     * @var string
     */
    public $note;

    /**
     * @var array
     */
    public $additional;

    /**
     * @param int $priceCent
     * @param string $currency
     * @param BaseActiveRecord|null $priceTable
     * @param string|null $note
     * @return static
     * @inheritdoc
     */
    public static function create(
        int $priceCent,
        string $currency,
        ?BaseActiveRecord $priceTable,
        ?string $note = null,
    ): static
    {
        $calculatedPrice = new static();
        $calculatedPrice->priceTable = $priceTable;
        $calculatedPrice->priceCent = $priceCent;
        $calculatedPrice->currency = $currency;
        $calculatedPrice->note = $note;
        $calculatedPrice->isSuccess = true;
        return $calculatedPrice;
    }

    /**
     * @param string|null $note
     * @return static
     * @inheritdoc
     */
    public static function createWithFailed(
        ?string $note = null,
    ): static
    {
        $calculatedPrice = new static();
        $calculatedPrice->note = $note;
        $calculatedPrice->isSuccess = false;
        return $calculatedPrice;
    }
}
