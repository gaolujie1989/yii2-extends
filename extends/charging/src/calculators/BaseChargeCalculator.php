<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\CalculatedPrice;
use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
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
    public $chargeItemLoader;

    /**
     * @var string
     */
    public $chargeType;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeItemLoader = Instance::ensure($this->chargeItemLoader, DataLoaderInterface::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param string $chargeType
     * @return CalculatedPrice|null
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model): array
    {
        /** @var BaseChargeItem[] $chargeItems */
        $chargeItems = $this->chargeItemLoader->get($model);
        if (empty($chargeItems)) {
            return [];
        }

        if (!is_array($chargeItems)) {
            $chargeItems = [$chargeItems];
        }

        $calculatedPrices = [];
        foreach ($chargeItems as $chargeItem) {
            $calculatedPrice = $this->calculateInternal($chargeItem);
            if ($calculatedPrice) {
                $calculatedPrice->chargeItem = $calculatedPrice->chargeItem ?: $chargeItem;
                $calculatedPrice->chargeType = $calculatedPrice->chargeType ?: $this->chargeType;
                $calculatedPrices[] = $calculatedPrice;
            }
        }

        return $calculatedPrices;
    }

    /**
     * @param BaseChargeItem $chargeItem
     * @param ChargePrice $chargePrice
     * @inheritdoc
     */
    abstract protected function calculateInternal(BaseChargeItem $chargeItem): ?CalculatedPrice;
}
