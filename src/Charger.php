<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\ValueHelper;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class Charger
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Charger extends Component implements BootstrapInterface
{
    public const EVENT_BEFORE_CHARGE = 'beforeCharge';

    public const EVENT_AFTER_CHARGE = 'afterCharge';

    public const EVENT_BEFORE_CALCULATE = 'beforeCalculate';

    public const EVENT_AFTER_CALCULATE = 'afterCalculate';

    /**
     * [
     *     'modelType'  => ['modelClass', ['chargeType1', 'chargeType2']]
     *     'modelType'  => ['modelClass', ['chargeType1' => $condition1]]
     * ]
     * @var array
     */
    public $chargeConfig = [];

    /**
     * @var array
     */
    public $chargeGroups = [];

    /**
     * @var array
     */
    public $chargePriceModelClasses = [];

    /**
     * @var DataLoaderInterface
     */
    public $chargeCalculatorLoader = 'chargeCalculatorLoader';

    /**
     * @var bool
     */
    public $calculateForceOnEvent = true;

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $this->listen();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->chargeCalculatorLoader = Instance::ensure($this->chargeCalculatorLoader, DataLoaderInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function listen(): void
    {
        foreach ($this->chargeConfig as [$modelClass]) {
            Event::on($modelClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'triggerChargeOnModelSaved']);
            Event::on($modelClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'triggerChargeOnModelSaved']);
            Event::on($modelClass, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'triggerChargeOnModelDeleted']);
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function triggerChargeOnModelSaved(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        $this->calculate($model, $this->calculateForceOnEvent);
    }

    /**
     * @param Event $event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function triggerChargeOnModelDeleted(Event $event): void
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        [$modelType, $chargeTypes] = $this->getModelChargeTypes($model);
        foreach ($chargeTypes as $chargeType) {
            /** @var ChargePrice $chargePriceModelClass */
            $chargePriceModelClass = $this->chargePriceModelClasses[$chargeType] ?? ChargePrice::class;
            $chargePrices = $chargePriceModelClass::find()
                ->chargeType($chargeType)
                ->modelType($modelType)
                ->modelId($model->getPrimaryKey())
                ->all();
            foreach ($chargePrices as $chargePrice) {
                $chargePrice->delete();
            }
        }
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
    public function calculate(BaseActiveRecord $model, bool $force = false): array
    {
        $chargeEvent = new ChargeEvent();
        $chargeEvent->model = $model;
        [$chargeEvent->modelType, $chargeEvent->chargeTypes] = $this->getModelChargeTypes($model);
        $this->trigger(self::EVENT_BEFORE_CHARGE, $chargeEvent);
        if ($chargeEvent->calculated) {
            return $chargeEvent->chargePrices;
        }

        $modelType = $chargeEvent->modelType;
        $modelId = $model->getPrimaryKey();
        foreach ($chargeEvent->chargeTypes as $chargeType) {
            /** @var ChargePrice $chargePriceModelClass */
            $chargePriceModelClass = $this->chargePriceModelClasses[$chargeType] ?? ChargePrice::class;
            $chargePrice = $chargePriceModelClass::find()
                ->modelId($modelId)
                ->modelType($modelType)
                ->chargeType($chargeType)
                ->one();
            if ($chargePrice === null) {
                $chargePrice = new $chargePriceModelClass();
                $chargePrice->charge_type = $chargeType;
                $chargePrice->charge_group = $this->chargeGroups[$chargeType] ?? '';
                $chargePrice->model_type = $modelType;
                $chargePrice->model_id = $modelId;
            }
            if ($force || $chargePrice->getIsNewRecord()
                || in_array($chargePrice->status, [ChargePrice::STATUS_ESTIMATE, ChargePrice::STATUS_FAILED], true)) {
                $this->calculateInternal($chargePrice, $model);
            }
            $chargeEvent->chargePrices[$chargeType] = $chargePrice;
        }
        $toDeleteChargePriceQuery = ChargePrice::find()->modelType($modelType)->modelId($modelId);
        if ($chargeEvent->chargeTypes) {
            $toDeleteChargePriceQuery->notChargeType($chargeEvent->chargeTypes);
        }
        foreach ($toDeleteChargePriceQuery->all() as $chargePrice) {
            $chargePrice->delete();
        }

        $this->trigger(self::EVENT_AFTER_CHARGE, $chargeEvent);

        return $chargeEvent->chargePrices;
    }

    /**
     * @param ChargePrice $chargePrice
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function recalculate(ChargePrice $chargePrice): bool
    {
        /** @var BaseActiveRecord $modelClass */
        [$modelClass] = $this->chargeConfig[$chargePrice->model_type];
        $model = $modelClass::findOne($chargePrice->model_id);
        if ($model === null) {
            return false;
        }
        $this->calculateInternal($chargePrice, $model);
        return true;
    }

    /**
     * @param ChargePrice $chargePrice
     * @param BaseActiveRecord $model
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function calculateInternal(ChargePrice $chargePrice, BaseActiveRecord $model): void
    {
        $calculateEvent = new CalculateEvent();
        $calculateEvent->chargePrice = $chargePrice;
        $calculateEvent->model = $model;
        $this->trigger(self::EVENT_BEFORE_CALCULATE, $calculateEvent);
        if ($calculateEvent->calculated) {
            return;
        }

        /** @var ChargeCalculatorInterface $chargeCalculator */
        $chargeCalculator = $this->chargeCalculatorLoader->get($chargePrice->charge_type);
        $chargeCalculator = Instance::ensure($chargeCalculator, ChargeCalculatorInterface::class);
        $chargePrice = $chargeCalculator->calculate($model, $chargePrice);
        $chargePrice->save(false);

        $this->trigger(self::EVENT_AFTER_CALCULATE, $calculateEvent);
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
    protected function filterChangeTypes(array $chargeTypes, $model): array
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
