<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\snapshot\behaviors;

use lujie\extend\helpers\ValueHelper;
use yii\base\Behavior;
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
class SnapshotBehavior extends Behavior
{
    public const EVENT_BEFORE_CREATE_SNAPSHOOT = 'beforeCreateSnapshot';
    public const EVENT_AFTER_CREATE_SNAPSHOOT = 'afterCreateSnapshot';

    /**
     * @var BaseActiveRecord
     */
    public $snapshotModelClass;

    /**
     * @var string
     */
    public $snapshotIdAttribute = 'snapshot_id';

    /**
     * @var string
     */
    public $timestampAttribute = 'updated_at';

    /**
     * @var bool
     */
    public $skipOnUnchanged = true;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'createModelSnapshot',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'createModelSnapshot',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createModelSnapshot(AfterSaveEvent $event): ?BaseActiveRecord
    {
        if ($this->skipOnUnchanged && empty($event->changedAttributes)) {
            return null;
        }

        if (empty($this->snapshotModelClass)) {
            $this->snapshotModelClass = get_class($this->owner) . 'Snapshot';
        }

        $snapshotEvent = new SnapshotEvent();
        $snapshotEvent->changedAttributes = $event->changedAttributes;
        $this->owner->trigger(self::EVENT_BEFORE_CREATE_SNAPSHOOT, $snapshotEvent);
        if ($snapshotEvent->created) {
            return $snapshotEvent->snapshot;
        }

        $currentSnapshot = null;
        if ($this->snapshotIdAttribute && $this->timestampAttribute) {
            $snapshotId = $this->owner->getAttribute($this->snapshotIdAttribute);
            $currentSnapshot = $snapshotId ? $this->snapshotModelClass::findOne($snapshotId) : null;
            $snapshotUpdatedAt = $currentSnapshot ? $currentSnapshot->getAttribute($this->timestampAttribute) : 0;
            $ownerUpdatedAt = $this->owner->getAttribute($this->timestampAttribute);
            if ($snapshotUpdatedAt && $ownerUpdatedAt && $snapshotUpdatedAt >= $ownerUpdatedAt) {
                return $currentSnapshot;
            }
        }

        /** @var BaseActiveRecord $snapshot */
        $snapshot = new $this->snapshotModelClass();
        $values = $this->owner->getAttributes(null, $snapshot::primaryKey());
        $values = array_filter($values, static function($v) {
            return $v !== null;
        });
        $snapshot->setAttributes($values, false);
        $callable = function () use ($snapshot) {
            if ($snapshot->save(false)) {
                if ($this->snapshotIdAttribute) {
                    if ($this->owner->updateAttributes([$this->snapshotIdAttribute => $snapshot->getPrimaryKey()])) {
                        return true;
                    }
                } else {
                    return true;
                }
                throw new Exception('Update snapshotID failed.');
            }
            return false;
        };

        $db = $this->snapshotModelClass::getDb();
        if ($db instanceof Connection) {
            $result = $db->transaction($callable);
        } else {
            $result = $callable();
        }

        $snapshotEvent->snapshot = $snapshot;
        $this->owner->trigger(self::EVENT_AFTER_CREATE_SNAPSHOOT, $event);
        return $result ? $snapshot : null;
    }
}
