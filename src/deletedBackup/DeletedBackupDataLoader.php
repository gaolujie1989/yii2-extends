<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup;

use lujie\data\loader\BaseDataLoader;
use lujie\extend\helpers\ClassHelper;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\BaseActiveRecord;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * Class DeletedBackupDataLoader
 * @package lujie\common\deleted\backup
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupDataLoader extends BaseDataLoader
{
    /**
     * @var array
     */
    public $rowFields = [];

    /**
     * @param $key
     * @return array
     * @inheritdoc
     */
    public function get($key): array
    {
        /** @var BaseActiveRecord $model */
        $model = $key;
        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $modelType = ClassHelper::getClassShortName($baseRecordClass);
        $rowFields = $this->rowFields[$baseRecordClass] ?? $this->rowFields[$modelType] ?? [];
        return [
            'model_type' => $modelType,
            'model_class' => $model::class,
            'table_name' => $this->getTableName($model),
            'row_id' => $model->getPrimaryKey() ?: 0,
            'row_key' => $this->getRowKey($model, $rowFields['row_key'] ?? null) ?: '',
            'row_parent_id' => $this->getRowParentId($model, $rowFields['row_parent_id'] ?? null) ?: 0,
            'row_data' => $model->attributes,
        ];
    }

    /**
     * @param BaseActiveRecord $model
     * @param string|null $keyAttribute
     * @return string|null
     * @inheritdoc
     */
    protected function getRowKey(BaseActiveRecord $model, ?string $keyAttribute): ?string
    {
        if ($keyAttribute) {
            return $model->{$keyAttribute};
        }
        $attributes = $model->getAttributes(null, $model::primaryKey());
        foreach ($attributes as $key => $value) {
            if (str_ends_with($key, '_key') || str_ends_with($key, '_no') || str_ends_with($key, '_code')) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @param BaseActiveRecord $model
     * @param string|null $parentIdAttribute
     * @return int|null
     * @inheritdoc
     */
    protected function getRowParentId(BaseActiveRecord $model, ?string $parentIdAttribute): ?int
    {
        if ($parentIdAttribute) {
            return $model->{$parentIdAttribute};
        }
        $attributes = $model->getAttributes(null, $model::primaryKey());
        foreach ($attributes as $key => $value) {
            if (str_ends_with($key, '_id')) {
                return $value;
            }
        }
        return null;
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
}
