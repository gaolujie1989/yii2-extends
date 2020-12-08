<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\backup\delete\behaviors;

use lujie\ar\backup\delete\models\DeletedData;
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
     * @var DeletedData
     */
    public $backupModelClass = DeletedData::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [BaseActiveRecord::EVENT_AFTER_DELETE => [$this, 'backupModelData']];
    }

    /**
     * @param Event $event
     * @throws Exception
     * @inheritdoc
     */
    public function backupModelData(Event $event): void
    {
        /** @var BaseActiveRecord $sender */
        $sender = $event->sender;
        /** @var BaseActiveRecord $backupModel */
        $backupModel = new $this->backupModelClass();
        $backupModel->setAttributes([
            'table_name' => $this->getTableName($sender),
            'row_id' => $sender->getPrimaryKey(),
            'row_data' => $sender->getAttributes(),
        ]);
        if (!$backupModel->save(false)) {
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
        $deletedData = $this->backupModelClass::find()
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
