<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\ar;

use lujie\extend\helpers\ClassHelper;
use yii\base\Application;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordSnapshotManager
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SnapshotManager extends BaseActiveRecordManager
{
    /**
     * @var array
     */
    public $snapshotClasses = [];

    /**
     * @var string
     */
    public $snapshotIdAttributes = [];

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'createSnapshot']);
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'createSnapshot']);
    }

    /**
     * @param AfterSaveEvent $event
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createSnapshot(AfterSaveEvent $event): ?BaseActiveRecord
    {
        if (empty($event->changedAttributes)) {
            return null;
        }
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if (!$this->isActive($model)) {
            return null;
        }
        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $snapshotClass = $this->snapshotClasses[$baseRecordClass] ?? $baseRecordClass . 'Snapshot';
        if (!class_exists($snapshotClass)) {
            return null;
        }

        /** @var BaseActiveRecord $snapshot */
        $snapshot = new $snapshotClass();
        $values = $model->getAttributes(null, $snapshot::primaryKey());
        $values = array_filter($values, static fn($v) => $v !== null);
        $snapshot->setAttributes($values, false);

        $snapshotIdAttribute = $this->snapshotIdAttributes[$baseRecordClass] ?? 'snapshot_id';
        if ($snapshot->save(false) && $model->hasAttribute($snapshotIdAttribute)) {
            $model->update([$snapshotIdAttribute => $snapshot->getPrimaryKey()]);
        }

        return $snapshot;
    }
}
