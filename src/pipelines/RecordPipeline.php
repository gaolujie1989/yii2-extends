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
class RecordPipeline extends ActiveRecordPipeline
{
    /**
     * @var DataSource
     */
    public $dataSource;

    /**
     * @var DataRecord
     */
    public $modelClass = DataRecord::class;

    /**
     * @param array $values
     * @return BaseActiveRecord|DataRecord
     * @inheritdoc
     */
    protected function createModel(array $values): BaseActiveRecord
    {
        $dataSource = $this->dataSource;
        $query = $this->modelClass::find()
            ->dataAccountId($dataSource->data_account_id)
            ->dataSourceType($dataSource->type);

        $model = null;
        $record = $values['record'];
        if (!empty($record['data_id'])) {
            $model = $query->dataId($record['data_id'])
                ->one();
        } elseif (!empty($record['data_parent_id']) && !empty($record['data_key'])) {
            $model = $query->dataParentId($record['data_parent_id'])
                ->dataKey($record['data_key'])
                ->one();
        }
        if (empty($model)) {
            /** @var DataRecord $model */
            $model = new $this->modelClass();
            $model->setAttributes([
                'data_account_id' => $dataSource->data_account_id,
                'data_source_type' => $dataSource->type,
            ]);
        }
        $model->setAttributes($record);
        return $model;
    }

    /**
     * @param array $data
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createModels(array $data): array
    {
        if (empty($this->dataSource) || !($this->dataSource instanceof DataSource)) {
            throw new InvalidConfigException('Source can not be empty and must be instanceof DataSource');
        }

        $models = [];
        foreach ($data as $key => $values) {
            $models[$key] = $this->createModel($values);
        }
        return $models;
    }
}
