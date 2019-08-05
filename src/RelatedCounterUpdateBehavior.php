<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\relation\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class RelatedValueUpdateBehavior
 * @package lujie\ar\relation\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RelatedCounterUpdateBehavior extends Behavior
{
    /**
     * @var string
     */
    public $valueAttribute;

    /**
     * @var string
     */
    public $relation;

    /**
     * @var string
     */
    public $relationValueAttribute;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterInsert(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord|null $relation */
        $relation = $this->{$this->relation};
        if ($relation === null) {
            throw new InvalidConfigException('Null relation model');
        }
        $relation->updateCounters([$this->relationValueAttribute => $event->sender->{$this->valueAttribute}]);
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterUpdate(AfterSaveEvent $event): void
    {
        if (isset($event->changedAttributes[$this->valueAttribute])) {
            /** @var BaseActiveRecord|null $relation */
            $relation = $this->{$this->relation};
            if ($relation === null) {
                throw new InvalidConfigException('Null relation model');
            }

            $diff = $event->sender->{$this->valueAttribute} - $event->changedAttributes[$this->valueAttribute];
            $relation->updateCounters([$this->relationValueAttribute => $diff]);
        }
    }

    /**
     * @param Event $event
     * @inheritdoc
     */
    public function afterDelete(Event $event): void
    {
        /** @var BaseActiveRecord|null $relation */
        $relation = $this->{$this->relation};
        if ($relation === null) { //for delete, it may be also deleted
            return;
        }
        $relation->updateCounters([$this->relationValueAttribute => -$event->sender->{$this->valueAttribute}]);
    }
}
