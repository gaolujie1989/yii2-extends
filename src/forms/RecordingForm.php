<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\BaseDataRecorder;
use lujie\data\recording\models\DataSource;
use lujie\executing\Executor;
use lujie\extend\constants\ExecStatusConst;
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
     * @var DataLoaderInterface
     */
    public $dataAccountLoader = 'dataAccountLoader';

    /**
     * @var int
     */
    public $dataSourceId;

    /**
     * @var ?Executor
     */
    public $executor;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['dataSourceId'], 'required'],
        ];
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

        $dataSource = DataSource::findOne($this->dataSourceId);
        if ($dataSource === null) {
            $this->addError('dataSourceId', "Invalid dataSourceId {$this->dataSourceId}, Null DataSource");
            return false;
        }
        if ($dataSource->last_exec_status === ExecStatusConst::EXEC_STATUS_SUCCESS) {
            $this->addError('dataSourceId', "DataSourceId {$this->dataSourceId} already executed success");
            return false;
        }
        $this->dataAccountLoader = Instance::ensure($this->dataAccountLoader, DataLoaderInterface::class);
        $dataAccount = $this->dataAccountLoader->get($dataSource->data_account_id);
        if ($dataAccount === null) {
            $this->addError('dataSourceId', "Invalid dataSourceId {$this->dataSourceId}, Null DataAccount");
            return false;
        }

        $this->dataRecorderLoader = Instance::ensure($this->dataRecorderLoader, DataLoaderInterface::class);
        $dataRecorder = $this->dataRecorderLoader->get($dataSource->type)
            ?: $this->dataRecorderLoader->get($dataAccount['type']);
        if ($dataRecorder === null) {
            $this->addError('dataSourceId', "Invalid dataSourceId {$this->dataSourceId}, Null DataRecorder");
            return false;
        }

        /** @var BaseDataRecorder $dataRecorder */
        $dataRecorder = Instance::ensure($dataRecorder, BaseDataRecorder::class);
        $dataRecorder->prepare($dataSource);
        if ($this->executor) {
            Yii::info("Execute data dataRecorder {$dataRecorder->getId()} with executor", __METHOD__);
            $this->executor = Instance::ensure($this->executor, Executor::class);
            return (bool)$this->executor->handle($dataRecorder);
        }
        Yii::info("Execute data dataRecorder {$dataRecorder->getId()}", __METHOD__);
        return $dataRecorder->execute();
    }
}
