<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\backup\delete;

use lujie\ar\backup\delete\models\DeletedData;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * Class DeletedBackupManager
 * @package lujie\ar\backup\delete
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupManager extends BaseObject implements BootstrapInterface
{
    /**
     * @var array
     */
    public $backupModelClasses = [];

    /**
     * @var DeletedData
     */
    public $storageModelClass = DeletedData::class;

    /**
     * @var bool
     */
    public $only = false;

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if ($this->only) {
            foreach ($this->backupModelClasses as $backupModelClass => $config) {
                Event::on($backupModelClass, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'afterDeleted']);
            }
        } else {
            Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'afterDeleted']);
        }
    }

    /**
     * @param Event $event
     * @inheritdoc
     */
    public function afterDeleted(Event $event): void
    {
        /** @var BaseActiveRecord $sender */
        $sender = $event->sender;
        $this->backup($sender);
    }

    /**
     * @param BaseActiveRecord $model
     * @return bool
     * @inheritdoc
     */
    public function backup(BaseActiveRecord $model): bool
    {
        $storageModel = new $this->storageModelClass();
        $storageModel->setAttributes([
            'table_name' => $this->getTableName($model),
            'row_id' => $model->getPrimaryKey(),
            'row_data' => $model->getAttributes(),
        ]);
        foreach ($this->backupModelClasses as $backupModelClass => $config) {
            if ($model instanceof $backupModelClass) {
                foreach ($config as $key => $attribute) {
                    $storageModel->{$key} = $model->{$attribute};
                }
                break;
            }
        }
        return $storageModel->save(false);
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
     * @param string|BaseActiveRecord $modelClass
     * @param bool $deleteBackup
     * @return bool
     * @throws Exception
     * @throws NotSupportedException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function restore(int $rowId, string $modelClass, bool $deleteBackup = false): bool
    {
        $model = $modelClass::instance();
        $deletedData = $this->storageModelClass::find()
            ->tableName($this->getTableName($model))
            ->rowId($rowId)
            ->one();

        if ($deletedData === null) {
            return false;
        }

        if ($model instanceof DbActiveRecord) {
            $execute = $model::getDb()->createCommand()->insert($model::tableName(), $deletedData->row_data)->execute();
            if ($execute && $deleteBackup) {
                $deletedData->delete();
            }
            return (bool)$execute;
        }
        if ($model instanceof MongodbActiveRecord) {
            $execute = $model::getDb()->createCommand()->insert($model::getCollection()->name, $deletedData->row_data);
            if ($execute && $deleteBackup) {
                $deletedData->delete();
            }
            return (bool)$execute;
        }
        throw new NotSupportedException('Restore for redis is not support');
    }
}
