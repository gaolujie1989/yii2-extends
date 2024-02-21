<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history;

use lujie\data\loader\BaseDataLoader;
use lujie\extend\helpers\ClassHelper;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordSnapshotManager
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryDataLoader extends BaseDataLoader
{
    /**
     * @var string[] Attributes that will not be saved in history.
     */
    public $skipAttributes = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'version',
    ];

    /**
     * @var array
     */
    public $onlyAttributes = [];

    /**
     * @var array
     */
    public $exceptAttributes = [];

    /**
     * @var array
     */
    public $modelFields = [];

    /**
     * @param $key
     * @inheritdoc
     */
    public function get($key): ?array
    {
        /** @var AfterSaveEvent $event */
        $event = $key;
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $modelType = ClassHelper::getClassShortName($baseRecordClass);

        $changedAttributes = $event->changedAttributes;
        $changedAttributes = array_diff_key($changedAttributes, array_flip($this->skipAttributes));
        if (empty($changedAttributes)) {
            return null;
        }
        $onlyAttributes = $this->onlyAttributes[$baseRecordClass] ?? $this->onlyAttributes[$modelType] ?? [];
        $exceptAttributes = $this->exceptAttributes[$baseRecordClass] ?? $this->exceptAttributes[$modelType] ?? [];
        if ($onlyAttributes) {
            $changedAttributes = array_intersect_key($changedAttributes, array_flip($onlyAttributes));
        }
        if ($exceptAttributes) {
            $changedAttributes = array_diff_key($changedAttributes, array_flip($exceptAttributes));
        }
        if (empty($changedAttributes)) {
            return null;
        }

        $modelFields = $this->modelFields[$baseRecordClass] ?? $this->modelFields[$modelType] ?? [];
        $changedDetails = $this->getChangedDetails($model, $changedAttributes);
        if (empty($changedDetails)) {
            return null;
        }
        return [
            'model_type' => $modelType,
            'model_class' => $model::class,
            'model_id' => $model->getPrimaryKey() ?: 0,
            'model_key' => $this->getModelKey($model, $modelFields['model_key'] ?? null) ?: '',
            'model_parent_id' => $this->getModelParentId($model, $modelFields['model_parent_id'] ?? null) ?: 0,
            'details' => $changedDetails,
        ];
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $changedAttributes
     * @return array
     * @inheritdoc
     */
    public function getChangedDetails(BaseActiveRecord $model, array $changedAttributes): array
    {
        $details = [];
        foreach ($changedAttributes as $changedAttribute => $oldValue) {
            if (is_array($oldValue) || is_array($model->{$changedAttribute})) {
                continue;
            }
            $details[] = [
                'changed_attribute' => $changedAttribute,
                'old_value' => $oldValue,
                'new_value' => $model->{$changedAttribute},
            ];
        }
        return $details;
    }

    /**
     * @param BaseActiveRecord $model
     * @param string|null $keyAttribute
     * @return string|null
     * @inheritdoc
     */
    protected function getModelKey(BaseActiveRecord $model, ?string $keyAttribute): ?string
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
    protected function getModelParentId(BaseActiveRecord $model, ?string $parentIdAttribute): ?int
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
}
