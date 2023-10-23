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
use function Psl\Vec\filter_nulls;

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
    public $historyDataLoader = HistoryDataLoader::class;

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
        $this->historyDataLoader = Instance::ensure($this->historyDataLoader, DataLoaderInterface::class);
    }

    /**
     * @param AfterSaveEvent $event
     * @return BaseActiveRecord|null
     * @throws \Throwable
     * @inheritdoc
     */
    public function createHistory(AfterSaveEvent $event): ?BaseActiveRecord
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if (!$this->isActive($model)) {
            return null;
        }

        $historyData = $this->historyDataLoader->get($event);
        if (empty($historyData)) {
            return null;
        }
        $baseRecordClass = ClassHelper::getBaseRecordClass($model);
        $historyClass = $this->historyClasses[$baseRecordClass] ?? $this->historyClass;
        $historyModel = new $historyClass();
        $historyModel->setAttributes($historyData);
        $historyModel->save(false);
        return $historyModel;
    }
}
