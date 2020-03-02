<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\snapshoot\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Class ConditionSnapshootBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\ar\snapshoot\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConditionalSnapshootBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute;

    /**
     * @var array
     */
    public $snapshootOn = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            SnapshootBehavior::EVENT_BEFORE_CREATE_SNAPSHOOT => 'beforeCreateSnapshoot',
        ];
    }

    /**
     * @param SnapshootEvent $event
     * @inheritdoc
     */
    public function beforeCreateSnapshoot(SnapshootEvent $event): void
    {
        if (array_key_exists($this->attribute, $event->changedAttributes)
            && in_array($this->owner->getAttribute($this->attribute), $this->snapshootOn, true)) {
            return;
        }
        $event->created = true;
    }
}
