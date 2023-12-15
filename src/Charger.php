<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ChargePrice;
use lujie\data\exchange\DataExchanger;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\ValueHelper;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class Charger
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Charger extends Component
{
    public const EVENT_BEFORE_CHARGE = 'beforeCharge';

    public const EVENT_AFTER_CHARGE = 'afterCharge';

    /**
     * [
     *     'modelType'  => ['modelClass', ['chargeType1', 'chargeType2']]
     *     'modelType'  => ['modelClass', ['chargeType1' => $condition1]]
     * ]
     * @var array
     */
    public $chargeConfig = [];

    /**
     * @var DataLoaderInterface
     */
    public $chargeCalculatorLoader = 'chargeCalculatorLoader';

    /**
     * @var DataExchanger
     */
    public $calculatedPriceImporter;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeCalculatorLoader = Instance::ensure($this->chargeCalculatorLoader, DataLoaderInterface::class);
        $this->calculatedPriceImporter = Instance::ensure($this->calculatedPriceImporter, DataExchanger::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param bool $force
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model): array
    {
        $chargeEvent = new ChargeEvent();
        $chargeEvent->model = $model;
        [$chargeEvent->modelType, $chargeEvent->chargeTypes] = $this->getModelChargeTypes($model);
        $this->trigger(self::EVENT_BEFORE_CHARGE, $chargeEvent);
        if ($chargeEvent->calculated) {
            $this->calculatedPriceImporter->exchange($chargeEvent->calculatedPrices);
            return $chargeEvent->calculatedPrices;
        }

        foreach ($chargeEvent->chargeTypes as $chargeType) {
            /** @var ChargeCalculatorInterface $chargeCalculator */
            $chargeCalculator = $this->chargeCalculatorLoader->get($chargeType);
            $calculatedPrices = $chargeCalculator->calculate($model, $chargeType);
            foreach ($calculatedPrices as $calculatedPrice) {
                $calculatedPrice->chargeModel = $model;
                $calculatedPrice->modelType = $chargeEvent->modelType;
            }
            $chargeEvent->calculatedPrices[] = $calculatedPrices;
        }
        if ($chargeEvent->calculatedPrices) {
            $chargeEvent->calculatedPrices = array_merge(...$chargeEvent->calculatedPrices);
        }

        $this->trigger(self::EVENT_AFTER_CHARGE, $chargeEvent);
        $this->calculatedPriceImporter->exchange($chargeEvent->calculatedPrices);
        return $chargeEvent->calculatedPrices;
    }

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function getModelChargeTypes(BaseActiveRecord $model): array
    {
        foreach ($this->chargeConfig as $modelType => [$modelClass, $chargeTypes]) {
            if ($model instanceof $modelClass) {
                return [$modelType, $this->filterChangeTypes($chargeTypes, $model)];
            }
        }
        throw new InvalidArgumentException('Invalid model, no matched modelClass, charge type not found');
    }

    /**
     * @param array $chargeTypes
     * @param object|array $model
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function filterChangeTypes(array $chargeTypes, object|array $model): array
    {
        $filteredChargeTypes = [];
        foreach ($chargeTypes as $chargeType => $condition) {
            if (is_int($chargeType)) {
                $filteredChargeTypes[] = $condition;
            } elseif (ValueHelper::match($model, $condition)) {
                $filteredChargeTypes[] = $chargeType;
            }
        }
        return $filteredChargeTypes;
    }
}
