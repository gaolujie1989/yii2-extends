<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\pipelines;

use creocoder\flysystem\Filesystem;
use lujie\data\recording\models\DataRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\recording\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileRecordDataPipeline extends RecordPipeline
{
    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $filePathPrefix = 'record_data/';

    /**
     * @var string
     */
    public $filePathSuffix = '.bin';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fs = Instance::ensure($this->fs, Filesystem::class);
        $this->filePathPrefix = trim($this->filePathPrefix, "/ \t\n\r\0\x0B") . '/';
    }

    /**
     * @param array $values
     * @return BaseActiveRecord|DataRecord
     * @inheritdoc
     */
    public function createModel(array $values): BaseActiveRecord
    {
        $dataRecord = parent::createModel($values);
        $handler = function () use ($dataRecord, $values) {
            Yii::debug("Write file of record: {$dataRecord->data_record_id}", __METHOD__);
            $this->fs->write($this->getFilePath($dataRecord), $values['text']);
        };
        if ($dataRecord->getIsNewRecord()) {
            $dataRecord->on(BaseActiveRecord::EVENT_AFTER_INSERT, $handler);
        } else {
            $dataRecord->on(BaseActiveRecord::EVENT_AFTER_UPDATE, $handler);
        }
        return $dataRecord;
    }

    /**
     * @param DataRecord $record
     * @return string
     * @inheritdoc
     */
    public function getFilePath(DataRecord $record): string
    {
        $file = implode('/', [
            $record->data_account_id,
            $record->data_source_type,
            $record->data_id ?: $record->data_parent_id . '_' . $record->data_key
        ]);
        return $this->filePathPrefix . $file . $this->filePathSuffix;
    }
}
