<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center\pipelines;


use lujie\data\center\models\DataRecord;
use lujie\data\center\models\DataSource;
use lujie\data\exchange\pipelines\PipelineInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\center\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataRecordPipeline extends BaseObject implements PipelineInterface
{
    public $sourceId;

    /**
     * @var DataSource
     */
    private $source;

    /**
     * @var DataRecord
     */
    public $recordClass = DataRecord::class;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->sourceId)) {
            throw new InvalidConfigException('The "sourceId" property must be set');
        }
        $this->source = DataSource::findOne($this->sourceId);
        if (empty($this->source)) {
            throw new InvalidConfigException('Invalid sourceId');
        }
    }

    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $record = $this->createRecord($data);
        $record->setAttributes($data['record']);
        return $record->save(false);
    }

    /**
     * @param array $data
     * @return DataRecord
     * @inheritdoc
     */
    protected function createRecord(array $data): DataRecord
    {
        $query = $this->recordClass::find()
            ->dataAccountId($this->source->data_account_id)
            ->dataType($this->source->type);

        $record = null;
        if (isset($data['data_id'])) {
            $record = $query->dataId($data['data_id'])
                ->one();
        } elseif (isset($data['data_parent_id']) && $data['data_key']) {
            $record = $query->dataParentId($data['data_parent_id'])
                ->dataKey($data['data_key'])
                ->one();
        }
        if (empty($record)) {
            /** @var DataRecord $record */
            $record = new $this->recordClass([
                'data_account_id' => $this->source->data_account_id,
                'data_type' => $this->source->type,
            ]);
        }
        return $record;
    }
}
