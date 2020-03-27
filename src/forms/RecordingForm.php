<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\BaseDataRecorder;
use lujie\data\recording\models\DataSource;
use lujie\executing\Executor;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class RecordingSourceForm
 * @package kiwi\data\recording\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingForm extends Model
{
    /**
     * @var DataLoaderInterface
     */
    public $dataRecorderLoader = 'dataRecorderLoader';

    /**
     * @var int
     */
    public $dataSourceId;

    /**
     * @var Executor
     */
    public $executor;

    /**
     * @var DataSource
     */
    private $_dataSource;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['dataSourceId'], 'required'],
            ['dataSourceId', 'validateSourceId'],
        ];
    }


    /**
     * @return DataSource|null
     * @inheritdoc
     */
    protected function getDataSource(): ?DataSource
    {
        if ($this->_dataSource === null) {
            $this->_dataSource = DataSource::findOne($this->dataSourceId);
        }
        return $this->_dataSource;
    }

    /**
     * @inheritdoc
     */
    public function validateSourceId(): void
    {
        if ($this->getDataSource() === null) {
            $this->addError('dataSourceId', 'Invalid data source id, DataSource not found');
            return;
        }
        if ($this->getDataSource()->dataAccount === null) {
            $this->addError('dataSourceId', 'Invalid data source, DataAccount not found');
            return;
        }
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function recording(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->dataRecorderLoader = Instance::ensure($this->dataRecorderLoader, DataLoaderInterface::class);
        $dataRecorder = $this->dataRecorderLoader->get($this->getDataSource()->type)
            ?: $this->dataRecorderLoader->get($this->getDataSource()->dataAccount->type);
        if ($dataRecorder === null) {
            $this->addError('dataSourceId', 'Invalid dataSourceId, Null DataRecorder');
            return false;
        }

        /** @var BaseDataRecorder $dataRecorder */
        $dataRecorder = Instance::ensure($dataRecorder, BaseDataRecorder::class);
        $dataRecorder->prepare($this->getDataSource());
        if ($this->executor) {
            Yii::info("Execute data dataRecorder {$dataRecorder->getId()} with executor", __METHOD__);
            $this->executor = Instance::ensure($this->executor, Executor::class);
            return (bool)$this->executor->handle($dataRecorder);
        }
        Yii::info("Execute data dataRecorder {$dataRecorder->getId()}", __METHOD__);
        return $dataRecorder->execute();
    }
}
