<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;


use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecordImporter
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordPipeline extends BaseDbPipeline
{
    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string model scenario to import
     */
    public $modelScenario;

    /**
     * @var bool
     */
    public $runValidation = false;

    /**
     * @var bool
     */
    public $skipOnUnChanged = true;

    /**
     * @var bool
     */
    public $filterNull = true;

    /**
     * [
     *     'lineNum' => 'line num xxx has error xxx',
     * ]
     * @var array
     */
    protected $errors = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        if ($this->filterNull) {
            $data = array_map('array_filter', $data);
        }

        $this->errors = [];

        $modelClass = $this->modelClass;
        if ($this->indexKeys) {
            $data = $this->indexData($data);
        }
        $models = $this->createModels($data);
        if ($this->runValidation && !$modelClass::validateMultiple($models)) {
            $line = 1;
            foreach ($models as $model) {
                if ($model->hasErrors()) {
                    $this->errors[$line] = $model->getErrors();
                }
                $line++;
            }
            return false;
        }

        $callable = function () use ($models) {
            foreach ($models as $model) {
                $createOrUpdate = $model->getIsNewRecord() ? self::AFFECTED_CREATED : self::AFFECTED_UPDATED;
                if ($this->skipOnUnChanged && empty($model->getDirtyAttributes())) {
                    $this->affectedRowCounts[self::AFFECTED_SKIPPED]++;
                } else if ($model->save(false)) {
                    $this->affectedRowCounts[$createOrUpdate]++;
                } else {
                    $this->affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            return true;
        };

        $db = $modelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        }
        return $callable();
    }

    /**
     * @param $values
     * @return BaseActiveRecord[]
     * @inheritdoc
     */
    protected function createModels(array $data): array
    {
        $models = [];
        $modelClass = $this->modelClass;
        $dataChunks = array_chunk($data, $this->chunkSize, true);
        foreach ($dataChunks as $chunkedData) {
            $existModels = [];
            if ($this->indexKeys) {
                $conditions = ArrayHelper::getColumn($chunkedData, function ($values) {
                    return array_intersect_key($values, array_flip($this->indexKeys));
                }, false);
                array_unshift($conditions, 'OR');
                $existModels = $modelClass::find()
                    ->andWhere($conditions)
                    ->indexBy(function ($values) {
                        return $this->getIndexValue($values);
                    })->all();
            }
            foreach ($chunkedData as $indexValue => $values) {
                /** @var BaseActiveRecord $model */
                if ($this->indexKeys) {
                    $model = $existModels[$indexValue] ?? new $modelClass();
                } else {
                    $model = new $modelClass();
                }
                if ($this->modelScenario) {
                    $model->setScenario($this->modelScenario);
                }
                $model->setAttributes($values);
                $models[] = $model;
            }
        }
        return $models;
    }
}
