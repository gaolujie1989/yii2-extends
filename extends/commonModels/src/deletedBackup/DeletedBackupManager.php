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
    public $backupDataLoader = DeletedBackupDataLoader::class;

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
        $this->backupDataLoader = Instance::ensure($this->backupDataLoader, DataLoaderInterface::class);
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

        $deletedData = $this->backupDataLoader->get($model);
        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $backupClass = $this->backupClasses[$baseRecordClass] ?? $this->backupClass;
        $backupModel = new $backupClass();
        $backupModel->setAttributes($deletedData);
        $backupModel->save(false);
        return $backupModel;
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
