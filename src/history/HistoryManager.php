<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history;

use lujie\common\history\forms\ModelHistoryForLog;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\ar\BaseActiveRecordManager;
use lujie\extend\helpers\ClassHelper;
use yii\base\Application;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class ActiveRecordSnapshotManager
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryManager extends BaseActiveRecordManager
{
    /**
     * @var BaseActiveRecord
     */
    public $historyClass = ModelHistoryForLog::class;

    /**
     * @var array
     */
    public $historyClasses = [];

    /**
     * @var DataLoaderInterface
     */
    public $historyDataLoader;

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
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'createHistory']);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->historyDataLoader) {
            $this->historyDataLoader = Instance::ensure($this->historyDataLoader, DataLoaderInterface::class);
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createHistory(AfterSaveEvent $event): ?BaseActiveRecord
    {
        $changedAttributes = $event->changedAttributes;
        $changedAttributes = array_diff_key($changedAttributes, array_flip($this->skipAttributes));
        if (empty($changedAttributes)) {
            return null;
        }

        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if (!$this->isActive($model)) {
            return null;
        }

        $historyData = $this->historyDataLoader
            ? $this->historyDataLoader->get($event)
            : $this->getHistoryData($model, $changedAttributes);

        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $historyClass = $this->historyClasses[$baseRecordClass] ?? $this->historyClass;
        $historyModel = new $historyClass();
        $historyModel->setAttributes($historyData);
        $historyModel->save(false);
        return $historyModel;
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $changedAttributes
     * @return array
     * @inheritdoc
     */
    protected function getHistoryData(BaseActiveRecord $model, array $changedAttributes): array
    {
        $details = [];
        foreach ($changedAttributes as $changedAttribute => $oldValue) {
            $details[] = [
                'changed_attribute' => $changedAttribute,
                'old_value' => $oldValue,
                'new_value' => $model->{$changedAttribute},
            ];
        }
        $data = [
            'model_type' => ClassHelper::getClassShortName(ClassHelper::getBaseRecordClass($model)),
            'model_class' => $model::class,
            'model_id' => $model->getPrimaryKey() ?: 0,
            'model_key' => '',
            'model_parent_id' => 0,
            'details' => $details,
        ];
        $attributes = $model->getAttributes(null, $model::primaryKey());
        foreach ($attributes as $key => $value) {
            if (empty($data['model_key'])
                && (str_ends_with($key, '_key') || str_ends_with($key, '_no') || str_ends_with($key, '_code'))) {
                $data['model_key'] = $value;
            }
            if (empty($data['model_parent_id']) && str_ends_with($key, '_id')) {
                $data['model_parent_id'] = $value;
            }
            if ($data['model_key'] && $data['model_parent_id']) {
                break;
            }
        }
        return $data;
    }
}
