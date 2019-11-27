<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;


use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
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

    /**
     * [
     *     'modelType'  => ['modelClass', ['chargeTypes']]
     * ]
     * @var array
     */
    public $chargeConfig = [];

    /**
     * @var array
     */
    public $chargeGroups = [];

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
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
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
        [$modelType] = $this->getModelChargeTypes($model);
        $chargePrices = ChargePrice::find()->modelType($modelType)->modelId($model->getPrimaryKey())->all();
        foreach ($chargePrices as $chargePrice) {
            $chargePrice->delete();
        }
    }

    /**
     * @param BaseActiveRecord $model
     * @param bool $force
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, bool $force = false): array
    {
        $chargeEvent = new ChargeEvent();
        $chargeEvent->model = $model;
        [$chargeEvent->modelType, $chargeEvent->chargeTypes] = $this->getModelChargeTypes($model);
        $this->trigger(self::EVENT_BEFORE_CHARGE, $chargeEvent);
        if ($chargeEvent->handled) {
            return $chargeEvent->chargePrices;
        }

        $chargePrices = [];
        $modelType = $chargeEvent->modelType;
        foreach ($chargeEvent->chargeTypes as $chargeType) {
            $chargePrice = ChargePrice::find()
                ->modelId($model->getPrimaryKey())
                ->modelType($modelType)
                ->chargeType($chargeType)
                ->one();
            if ($chargePrice === null) {
                $chargePrice = new ChargePrice();
                $chargePrice->charge_type = $chargeType;
                $chargePrice->charge_group = $this->chargeGroups[$chargeType] ?? '';
                $chargePrice->model_type = $modelType;
                $chargePrice->model_id = $model->getPrimaryKey();
            }
            if ($force || $chargePrice->status === ChargePrice::STATUS_ESTIMATE || $chargePrice->getIsNewRecord()) {
                /** @var ChargeCalculatorInterface $chargeCalculator */
                $chargeCalculator = $this->chargeCalculatorLoader->get($chargeType);
                $chargeCalculator = Instance::ensure($chargeCalculator, ChargeCalculatorInterface::class);
                $chargePrice = $chargeCalculator->calculate($model, $chargePrice);
                $chargePrice->mustSave(false);
            }
            $chargePrices[$chargeType] = $chargePrice;
        }

        $chargeEvent->chargePrices = $chargePrices;
        $this->trigger(self::EVENT_AFTER_CHARGE, $chargeEvent);

        return $chargeEvent->chargePrices;
    }

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public function getModelChargeTypes(BaseActiveRecord $model): array
    {
        foreach ($this->chargeConfig as $modelType => [$modelClass, $chargeTypes]) {
            if ($model instanceof $modelClass) {
                return [$modelType, $chargeTypes];
            }
        }
        throw new InvalidArgumentException('Invalid model, no matched modelClass, charge type not found');
    }
}
