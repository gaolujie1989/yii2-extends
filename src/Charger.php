<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;


use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class Charger
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Charger extends BaseObject implements BootstrapInterface
{
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
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        foreach ($this->chargeConfig as [$modelClass]) {
            Event::on($modelClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'triggerChargeOnModelSaved']);
            Event::on($modelClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'triggerChargeOnModelSaved']);
        }
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
     * @param BaseActiveRecord $model
     * @param bool $force
     * @return array|ChargePrice[]
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, bool $force = false): array
    {
        [$modelType, $chargeTypes] = $this->getModelChargeTypes($model);
        $chargePrices = [];
        foreach ($chargeTypes as $chargeType) {
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
                $chargePrice->status = ChargePrice::STATUS_GENERATED;
            }
            if ($force || $chargePrice->status === ChargePrice::STATUS_GENERATED || $chargePrice->getIsNewRecord()) {
                /** @var ChargeCalculatorInterface $chargeCalculator */
                $chargeCalculator = $this->chargeCalculatorLoader->get($chargeType);
                $chargePrice = $chargeCalculator->calculate($model, $chargePrice);
                $chargePrice->mustSave(false);
            }
            $chargePrices[$chargeType] = $chargePrice;
        }
        return $chargePrices;
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
