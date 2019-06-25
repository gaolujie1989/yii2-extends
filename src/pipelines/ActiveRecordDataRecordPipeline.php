<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\pipelines;


use creocoder\flysystem\Filesystem;
use lujie\data\staging\models\DataRecord;
use lujie\data\staging\models\DataRecordData;
use lujie\data\staging\models\DataSource;
use lujie\data\exchange\pipelines\PipelineInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\staging\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataRecordPipeline extends DataRecordPipeline
{
    /**
     * @var DataRecordData
     */
    public $recordDataClass = DataRecordData::class;

    /**
     * @param array $data
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $record = $this->createRecord($data);
        $record->setAttributes($data['record']);

        return $this->recordClass::getDb()->transaction(function() use ($record, $data) {
            if ($record->save(false)) {
                /** @var DataRecordData $recordData */
                $recordData = $this->recordDataClass::findByDataRecordId($record->data_record_id)
                    ?: new $this->recordDataClass();
                $recordData->data_record_id = $record->data_record_id;
                $recordData->data_text = $data['text'];
                return $recordData->save(false);
            }
            return false;
        });
    }
}
