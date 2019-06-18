<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\snapshoot\behaviors;


use yii\base\Behavior;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Exception;

/**
 * Class VersionBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\arhistory
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SnapshootBehavior extends Behavior
{
    public const EVENT_BEFORE_CREATE_SNAPSHOOT = 'beforeCreateSnapshoot';
    public const EVENT_AFTER_CREATE_SNAPSHOOT = 'afterCreateSnapshoot';

    /**
     * @var BaseActiveRecord
     */
    public $snapshootModelClass;

    /**
     * @var string
     */
    public $snapshootIdAttribute = 'snapshoot_id';

    /**
     * @var string
     */
    public $timestampAttribute = 'updated_at';

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'createModelSnapshoot',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'createModelSnapshoot',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createModelSnapshoot(AfterSaveEvent $event): ?BaseActiveRecord
    {
        if (empty($this->snapshootModelClass)) {
            $this->snapshootModelClass = get_class($this->owner) . 'Snapshoot';
        }

        $snapshootEvent = new SnapshootEvent();
        $snapshootEvent->changedAttributes = $event->changedAttributes;
        $this->owner->trigger(self::EVENT_BEFORE_CREATE_SNAPSHOOT, $snapshootEvent);
        if ($snapshootEvent->created) {
            return $snapshootEvent->snapshoot;
        }

        $currentSnapshoot = null;
        if ($this->snapshootIdAttribute && $this->timestampAttribute) {
            $snapshootId = $this->owner->getAttribute($this->snapshootIdAttribute);
            $currentSnapshoot = $snapshootId ? $this->snapshootModelClass::findOne($snapshootId) : null;
            $snapshootUpdatedAt = $currentSnapshoot ? $currentSnapshoot->getAttribute($this->timestampAttribute) : 0;
            $ownerUpdatedAt = $this->owner->getAttribute($this->timestampAttribute);
            if ($snapshootUpdatedAt && $ownerUpdatedAt && $snapshootUpdatedAt >= $ownerUpdatedAt) {
                return $currentSnapshoot;
            }
        }

        /** @var BaseActiveRecord $snapshoot */
        $snapshoot = new $this->snapshootModelClass();
        $snapshoot->setAttributes($this->owner->getAttributes(), false);
        $callable = function () use ($snapshoot) {
            if ($snapshoot->save(false)) {
                if ($this->owner->updateAttributes([$this->snapshootIdAttribute => $snapshoot->getPrimaryKey()])) {
                    return true;
                }
                throw new Exception('Update snapshootID failed.');
            }
            return false;
        };

        $db = $this->snapshootModelClass::getDb();
        if ($db instanceof Connection) {
            $result = $db->transaction($callable);
        } else {
            $result = $callable();
        }

        $snapshootEvent->snapshoot = $snapshoot;
        $this->owner->trigger(self::EVENT_AFTER_CREATE_SNAPSHOOT, $event);
        return $result ? $snapshoot : null;
    }
}
