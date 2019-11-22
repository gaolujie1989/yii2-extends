<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseChargeCalculator extends BaseObject implements ChargeCalculatorInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $chargeableItemLoader = 'chargeableItemLoader';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeableItemLoader = Instance::ensure($this->chargeableItemLoader, DataLoaderInterface::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param ChargePrice $chargePrice
     * @return ChargePrice
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        /** @var ChargeableItem $chargeableItem */
        $chargeableItem = $this->chargeableItemLoader->get([$model, $chargePrice]);
        $tablePrice = $this->getTablePrice($chargeableItem);
        if ($tablePrice === null) {
            throw new InvalidConfigException('No matched shipping price');
        }

        $chargePrice->custom_type = $chargeableItem->customType;
        $chargePrice->qty = $chargeableItem->qty;
        $chargePrice->owner_id = $chargeableItem->ownerId;
        $chargePrice->parent_model_id = $chargeableItem->parentId;

        $chargePrice->price_table_id = $tablePrice['priceTableId'];
        $chargePrice->price_cent = $tablePrice['priceCent'];
        $chargePrice->currency = $tablePrice['currency'];
        return $chargePrice;
    }

    /**
     * @param ChargeableItem $chargeableItem
     * @return array|null
     * @inheritdoc
     */
    abstract public function getTablePrice(ChargeableItem $chargeableItem): ?array;
}
