<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\pipelines;


use creocoder\flysystem\Filesystem;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataRecordData;
use lujie\data\recording\models\DataSource;
use lujie\data\exchange\pipelines\PipelineInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\recording\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordRecordDataPipeline extends DataRecordPipeline
{
    /**
     * @var DataRecordData
     */
    public $recordDataClass = DataRecordData::class;

    /**
     * @param array $values
     * @return DataRecord
     * @inheritdoc
     */
    protected function createModel(array $values): BaseActiveRecord
    {
        $dataRecord = parent::createModel($values);
        if ($dataRecord->getIsNewRecord()) {
            /** @var DataRecordData $recordData */
            $recordData = new $this->recordDataClass();
            $recordData->data_text = $values['text'];
            $dataRecord->on(BaseActiveRecord::EVENT_AFTER_INSERT, static function(AfterSaveEvent $event) use ($recordData) {
                $recordData->data_record_id = $event->sender->data_record_id;
                $recordData->save(false);
            });
        } else {
            /** @var DataRecordData $recordData */
            $recordData = $this->recordDataClass::findByDataRecordId($dataRecord->data_record_id)
                ?: new $this->recordDataClass();
            $recordData->data_text = $values['text'];
            $dataRecord->on(BaseActiveRecord::EVENT_AFTER_UPDATE, static function() use ($recordData) {
                $recordData->save(false);
            });
        }
        return $dataRecord;
    }
}
