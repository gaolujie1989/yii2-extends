<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\pipelines;


use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataSource;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

/**
 * Class FileDataRecordPipeline
 * @package lujie\data\recording\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataRecordPipeline extends ActiveRecordPipeline
{
    public $sourceId;

    /**
     * @var DataSource
     */
    private $source;

    /**
     * @var DataRecord
     */
    public $modelClass = DataRecord::class;

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
     * @param array $values
     * @return BaseActiveRecord|DataRecord
     * @inheritdoc
     */
    protected function createModel(array $values): BaseActiveRecord
    {
        $query = $this->modelClass::find()
            ->dataAccountId($this->source->data_account_id)
            ->dataSourceType($this->source->type);

        $model = null;
        $record = $values['record'];
        if (isset($record['data_id'])) {
            $model = $query->dataId($record['data_id'])
                ->one();
        } elseif (isset($record['data_parent_id']) && $record['data_key']) {
            $model = $query->dataParentId($record['data_parent_id'])
                ->dataKey($record['data_key'])
                ->one();
        }
        if (empty($model)) {
            /** @var DataRecord $model */
            $model = new $this->modelClass();
            $model->setAttributes([
                'data_account_id' => $this->source->data_account_id,
                'data_source_id' => $this->source->data_source_id,
                'data_source_type' => $this->source->type,
            ]);
            $model->setAttributes($record);
        }
        return $model;
    }
}
