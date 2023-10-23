<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup;

use lujie\common\deleted\backup\models\DeletedBackup;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\ar\BaseActiveRecordManager;
use lujie\extend\helpers\ClassHelper;
use yii\base\Application;
use yii\base\Event;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\mongodb\ActiveRecord as MongoActiveRecord;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * Class ActiveRecordDeletedBackupManager
 * @package lujie\extend\ar
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupManager extends BaseActiveRecordManager
{
    /**
     * @var BaseActiveRecord
     */
    public $backupClass = DeletedBackup::class;

    /**
     * @var array
     */
    public $backupClasses = [];

    /**
     * @var DataLoaderInterface
     */
    public $backupDataLoader;

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'backupDeleted']);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->backupDataLoader) {
            $this->backupDataLoader = Instance::ensure($this->backupDataLoader, DataLoaderInterface::class);
        }
    }

    /**
     * @param Event $event
     * @return BaseActiveRecord|null
     * @inheritdoc
     */
    public function backupDeleted(Event $event): ?BaseActiveRecord
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if (!$this->isActive($model)) {
            return null;
        }

        $deletedData = $this->backupDataLoader
            ? $this->backupDataLoader->get($model)
            : $this->getDeletedBackupData($model);

        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $backupClass = $this->backupClasses[$baseRecordClass] ?? $this->backupClass;
        $backupModel = new $backupClass();
        $backupModel->setAttributes($deletedData);
        $backupModel->save(false);
        return $backupModel;
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $keyMap
     * @return array
     * @inheritdoc
     */
    protected function getDeletedBackupData(BaseActiveRecord $model): array
    {
        $data = [
            'model_type' => ClassHelper::getClassShortName(ClassHelper::getBaseRecordClass($model)),
            'model_class' => $model::class,
            'table_name' => $this->getTableName($model),
            'row_id' => $model->getPrimaryKey() ?: 0,
            'row_key' => '',
            'row_parent_id' => 0,
            'row_data' => $model->attributes,
        ];
        $attributes = $model->getAttributes(null, $model::primaryKey());
        foreach ($attributes as $key => $value) {
            if (empty($data['row_key'])
                && (str_ends_with($key, '_key') || str_ends_with($key, '_no') || str_ends_with($key, '_code'))) {
                $data['row_key'] = $value;
            }
            if (empty($data['row_parent_id']) && str_ends_with($key, '_id')) {
                $data['row_parent_id'] = $value;
            }
            if ($data['row_key'] && $data['row_parent_id']) {
                break;
            }
        }
        return $data;
    }

    /**
     * @param BaseActiveRecord $model
     * @return string
     * @inheritdoc
     */
    protected function getTableName(BaseActiveRecord $model): string
    {
        if ($model instanceof DbActiveRecord) {
            return $model::tableName();
        }
        if ($model instanceof MongodbActiveRecord) {
            return $model::collectionName();
        }
        if ($model instanceof RedisActiveRecord) {
            return $model::keyPrefix();
        }
        return '';
    }

    /**
     * @param BaseActiveRecord $backupModel
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function restoreDeleted(BaseActiveRecord $backupModel): bool
    {
        /** @var BaseActiveRecord $modelClass */
        $modelClass = $backupModel->getAttributes('model_class');
        $model = new $modelClass();
        if ($model instanceof DbActiveRecord) {
            return $model::getDb()
                    ->createCommand()
                    ->insert($model::tableName(), $backupModel->getAttribute('row_data'))
                    ->execute() > 0;
        }
        if ($model instanceof MongoActiveRecord) {
            return $model::getDb()
                    ->createCommand()
                    ->insert($model::collectionName(), $backupModel->getAttribute('row_data')) !== false;
        }
        return false;
    }
}
