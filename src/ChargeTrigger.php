<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class ChargeTrigger
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTrigger extends BaseObject implements BootstrapInterface
{
    /**
     * [
     *     'modelClass'  => ['attribute1' => 'value1']]
     * ]
     * @var array
     */
    public $chargeConfig = [];

    /**
     * @var Charger
     */
    public $charger = 'charger';

    /**
     * @var bool
     */
    public $calculateForce = false;

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'triggerChargeOnModelSaved']);
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'triggerChargeOnModelSaved']);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->charger = Instance::ensure($this->charger, Charger::class);
    }

    /**
     * @param AfterSaveEvent $event
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function triggerChargeOnModelSaved(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        foreach ($this->chargeConfig as $modelClass => $attributes) {
            if ($model instanceof $modelClass && $this->isModelMatched($model, $attributes)) {
                $this->charger->calculate($model, $this->calculateForce);
                break;
            }
        }
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $attributes
     * @return bool
     * @inheritdoc
     */
    protected function isModelMatched(BaseActiveRecord $model, array $attributes): bool
    {
        foreach ($attributes as $attribute => $value) {
            if ($model->{$attribute} !== $value) {
                return false;
            }
        }
        return true;
    }
}
