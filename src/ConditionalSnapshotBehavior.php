<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\snapshot\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Class ConditionSnapshotBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\ar\snapshot\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConditionalSnapshotBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute;

    /**
     * @var array
     */
    public $snapshotOn = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            SnapshotBehavior::EVENT_BEFORE_CREATE_SNAPSHOT => 'beforeCreateSnapshot',
        ];
    }

    /**
     * @param SnapshotEvent $event
     * @inheritdoc
     */
    public function beforeCreateSnapshot(SnapshotEvent $event): void
    {
        if (array_key_exists($this->attribute, $event->changedAttributes)
            && in_array($this->owner->getAttribute($this->attribute), $this->snapshotOn, true)) {
            return;
        }
        $event->created = true;
    }
}
