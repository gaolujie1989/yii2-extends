<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use yii\base\BaseObject;
use yii\base\Behavior;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\di\Instance;

/**
 * Class GlobalBehaviorAttachment
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GlobalBehaviorAttacher extends BaseObject implements BootstrapInterface
{
    /**
     * @var array|Behavior[]
     */
    public $behaviors = [];

    /**
     * @var array
     */
    public $modelClasses = [];

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $this->attach();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->behaviors as $key => $attachBehavior) {
            $this->behaviors[$key] = Instance::ensure($attachBehavior, Behavior::class);
        }
    }

    /**
     * attach behaviors
     * @inheritdoc
     */
    public function attach(): void
    {
        foreach ($this->behaviors as $key => $behavior) {
            $behaviorAttachModelClasses = $this->modelClasses[$key] ?? [];
            foreach ($behavior->events() as $event => $handler) {
                foreach ($behaviorAttachModelClasses as $modelClass) {
                    Event::on($modelClass, $event, [$this, 'triggerBehavior'], [$behavior, $handler]);
                }
            }
        }
    }

    /**
     * @param Event $event
     */
    public function triggerBehavior(Event $event): void
    {
        /** @var Behavior $behavior */
        [$behavior, $handler] = $event->data;
        $behavior->owner = $event->sender;
        $event->data = [];
        $behaviorHandler = is_string($handler) ? [$behavior, $handler] : $handler;
        $behaviorHandler($event);
    }
}
