<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\pipelines;


use creocoder\flysystem\Filesystem;
use lujie\data\staging\models\DataRecord;
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
class FileDataRecordPipeline extends DataRecordPipeline
{
    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $filePathPrefix = '';

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
    }

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
        $this->recordClass::getDb()->transaction(function() use ($record, $data) {
            if ($record->save(false)) {
                $this->fs->write($this->getFilePath($record), $data['text']);
                return true;
            }
            return false;
        });
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
            $record->data_type,
            $record->data_id ?: $record->data_parent_id . '_' . $record->data_key
        ]);
        return $this->filePathPrefix . $file . $this->filePathSuffix;
    }
}
