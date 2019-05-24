<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center\pipelines;


use creocoder\flysystem\Filesystem;
use lujie\data\center\models\DataRecord;
use lujie\data\exchange\pipelines\PipelineInterface;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\center\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileDataRecordPipeline extends BaseObject implements PipelineInterface
{
    /**
     * @var DataRecord
     */
    public $recordClass = DataRecord::class;

    /**
     * @var array
     */
    public $recordConfig = [];

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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fs = Instance::ensure($this->fs);
    }

    /**
     * @param array $data
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $this->recordClass::find()
            ->dataAccountId('')
            ->dataType('')
            ->dataId();

        /** @var DataRecord $record */
        $record = Yii::createObject($this->recordConfig);
        $record->setAttributes($data['record']);
        if (!$record->save()) {
            return false;
        }

        $this->fs->write($this->getFilePath($record), $data['compressedText']);
    }

    public function getFilePath(DataRecord $record)
    {
        $file = implode('/', [$record->data_account_id, $record->data_type, $record->data_id]);
        return $this->filePathPrefix . $file . $this->filePathSuffix;
    }
}
