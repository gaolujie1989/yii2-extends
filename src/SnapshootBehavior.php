<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\arsnapshoot;


use yii\base\Behavior;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;
use yii\db\Connection;

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
    const EVENT_BEFORE_CREATE_SNAPSHOOT = 'beforeCreateSnapshoot';
    const EVENT_AFTER_CREATE_SNAPSHOOT = 'afterCreateSnapshoot';

    /**
     * @var BaseActiveRecord
     */
    public $snapshootModelClass;

    /**
     * @var string
     */
    public $snapshootIdAttribute = 'current_snapshoot_id';

    /**
     * @var string
     */
    public $timestampAttribute = 'updated_at';

    /**
     * @var bool
     */
    public $createSnapshootOnUpdate = true;

    /**
     * @var bool
     */
    public $createSnapshootOnDelete = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->snapshootModelClass)) {
            $this->snapshootModelClass = get_class($this->owner) . 'Snapshoot';
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        $events = parent::events();
        if ($this->createSnapshootOnUpdate) {
            $events[BaseActiveRecord::EVENT_BEFORE_UPDATE] = [$this, 'createSnapshoot'];
        }
        if ($this->createSnapshootOnDelete) {
            $events[BaseActiveRecord::EVENT_BEFORE_DELETE] = [$this, 'createSnapshoot'];
        }
        return $events;
    }

    /**
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createSnapshoot()
    {
        $event = new ModelEvent();
        $this->owner->trigger(self::EVENT_BEFORE_CREATE_SNAPSHOOT, $event);
        if (!$event->isValid) {
            return null;
        }

        $currentSnapshoot = null;
        if ($this->snapshootIdAttribute && $this->timestampAttribute) {
            $snapshootId = $this->owner->getOldAttribute($this->snapshootIdAttribute);
            $ownerUpdatedAt = $this->owner->getOldAttribute($this->timestampAttribute);
            if ($snapshootId && $ownerUpdatedAt
                && $currentSnapshoot = $this->snapshootModelClass::findOne($snapshootId)) {
                $snapshootUpdatedAt = $currentSnapshoot->getAttribute($this->timestampAttribute);
                if ($snapshootUpdatedAt >= $ownerUpdatedAt) {
                    return $currentSnapshoot;
                }
            }
        }

        $callable = function () {
            /** @var BaseActiveRecord $snapshoot */
            $snapshoot = new $this->snapshootModelClass();
            $snapshoot->setAttributes($this->owner->getOldAttributes());
            $snapshoot->save(false);
            $this->owner->setAttribute($this->snapshootIdAttribute, $snapshoot->getPrimaryKey());
            $this->owner->save(false);
            return $snapshoot;
        };
        $db = $this->snapshootModelClass::getDb();
        if ($db instanceof Connection) {
            $snapshoot = $db->transaction($callable);
        } else {
            $snapshoot = call_user_func($callable);
        }

        $this->owner->trigger(self::EVENT_AFTER_CREATE_SNAPSHOOT, new Event());
        return $snapshoot;
    }
}
