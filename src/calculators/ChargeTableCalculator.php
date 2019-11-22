<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;


use lujie\charging\calculators\ChargeableItem;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ChargeTable;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableCalculator extends BaseObject implements ChargeCalculatorInterface
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
        $chargeTablePrice = $this->getChargeTablePrice($chargeableItem);
        if ($chargeTablePrice === null) {
            throw new InvalidConfigException('No matched shipping price');
        }

        $chargePrice->custom_type = $chargeableItem->customType;
        $chargePrice->price_table_id = $chargeTablePrice->charge_table_id;
        $chargePrice->price_cent = $chargeTablePrice->price_cent;
        $chargePrice->currency = $chargeTablePrice->currency;

        $chargePrice->qty = $chargeableItem->qty;
        $chargePrice->owner_id = $chargeableItem->ownerId;
        $chargePrice->parent_model_id = $chargeableItem->parentId;
        return $chargePrice;
    }

    /**
     * @param ChargeableItem $chargeableItem
     * @param string $chargeType
     * @return ChargeTable|null
     * @inheritdoc
     */
    public function getChargeTablePrice(ChargeableItem $chargeableItem): ?ChargeTable
    {
        return ChargeTable::find()
            ->ownerId($chargeableItem->ownerId)
            ->activeAt($chargeableItem->chargedAt ?: time())
            ->chargeType($chargeableItem->chargeType)
            ->customType($chargeableItem->customType)
            ->limitValue($chargeableItem->limitValue)
            ->one();
    }
}
