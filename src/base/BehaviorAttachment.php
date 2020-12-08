<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use yii\base\BaseObject;
use yii\base\Behavior;
use yii\base\Event;
use yii\di\Instance;

/**
 * Class BehaviorAttachment
 *
 * Global attach behavior to model classes
 *
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BehaviorAttachment extends BaseObject
{
    /**
     * @var string[]
     */
    public $attachModelClasses = [];

    /**
     * @var Behavior[]
     */
    public $attachBehaviors = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->attachBehaviors as $key => $attachBehavior) {
            $this->attachBehaviors[$key] = Instance::ensure($attachBehavior, Behavior::class);
        }
    }

    /**
     * attach behaviors
     */
    public function attach(): void
    {
        foreach ($this->attachModelClasses as $attachModelClass) {
            foreach ($this->attachBehaviors as $attachBehavior) {
                foreach ($attachBehavior->events() as $event => $handler) {
                    Event::on($attachModelClass, $event, [$this, 'triggerBehavior'], [$attachBehavior, $handler]);
                }
            }
        }
    }

    /**
     * @param Event $event
     */
    public function triggerBehavior(Event $event): void
    {
        /** @var Behavior $attachBehavior */
        [$attachBehavior, $handler] = $event->data;
        $attachBehavior->owner = $event->sender;
        $event->data = [];
        $behaviorHandler = is_string($handler) ? [$attachBehavior, $handler] : $handler;
        $behaviorHandler($event);
    }
}