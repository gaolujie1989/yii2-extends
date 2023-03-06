<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\deleted\backup\behaviors;

use lujie\ar\deleted\backup\models\DeletedData;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * Class HistoryBehaviors
 *
 * backup deleted active records
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupDeleteBehavior extends Behavior
{
    /**
     * @var string
     */
    public $keyAttribute;

    /**
     * @var string
     */
    public $parentIdAttribute;

    /**
     * @var DeletedData
     */
    public $storageModelClass = DeletedData::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_DELETE => [$this, 'backupModelData']
        ];
    }

    /**
     * @param Event $event
     * @throws Exception
     * @inheritdoc
     */
    public function backupModelData(Event $event): void
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        /** @var DeletedData $storageModel */
        $storageModel = new $this->storageModelClass();
        $storageModel->setAttributes([
            'table_name' => $this->getTableName($model),
            'row_id' => $model->getPrimaryKey(),
            'row_data' => $model->getAttributes(),
        ]);
        if ($this->keyAttribute) {
            $storageModel->row_key = $model->{$this->keyAttribute};
        }
        if ($this->parentIdAttribute) {
            $storageModel->row_parent_id = $model->{$this->parentIdAttribute};
        }
        if (!$storageModel->save(false)) {
            throw new Exception('Backup model data failed.');
        }
    }

    /**
     * @param BaseActiveRecord $record
     * @return string
     * @inheritdoc
     */
    protected function getTableName(BaseActiveRecord $record): string
    {
        if ($record instanceof DbActiveRecord) {
            return $record::tableName();
        }
        if ($record instanceof MongodbActiveRecord) {
            return $record::collectionName();
        }
        if ($record instanceof RedisActiveRecord) {
            return $record::keyPrefix();
        }
        return '';
    }

    /**
     * @param int $rowId
     * @param bool $deleteBackup
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function restoreModelData(int $rowId, bool $deleteBackup = false): bool
    {
        $owner = $this->owner;
        $deletedData = $this->storageModelClass::find()
            ->tableName($this->getTableName($owner))
            ->rowId($rowId)
            ->one();

        $owner->setIsNewRecord(true);
        $owner->setAttributes($deletedData->row_data, false);
        $success = $owner->save(false);
        if ($success && $deleteBackup) {
            $deletedData->delete();
        }
        return $success;
    }
}
