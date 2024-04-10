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
     * @var string|BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string model scenario to import
     */
    public $modelScenario;

    /**
     * @var bool
     */
    public $safeOnly = true;

    /**
     * @var bool
     */
    public $runValidation = false;

    /**
     * @var bool
     */
    public $skipOnUnChanged = true;

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
    protected function processInternal(array $data): bool
    {
        $this->errors = [];

        $modelClass = $this->modelClass;
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
            $affectedRowCounts = $this->affectedRowCounts;
            foreach ($models as $model) {
                $createOrUpdate = $model->getIsNewRecord() ? self::AFFECTED_CREATED : self::AFFECTED_UPDATED;
                if ($this->skipOnUnChanged && empty($model->getDirtyAttributes())) {
                    $affectedRowCounts[self::AFFECTED_SKIPPED]++;
                } elseif ($model->save(false)) {
                    $affectedRowCounts[$createOrUpdate]++;
                } else {
                    $affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            $this->affectedRowCounts = $affectedRowCounts;
            return true;
        };

        $db = $modelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        }
        return $callable();
    }

    /**
     * @param array $data
     * @return BaseActiveRecord[]
     * @inheritdoc
     */
    protected function createModels(array $data): array
    {
        $models = [];
        $filterInsertAttributes = $this->insertOnlyAttributes || $this->insertExceptAttributes;
        $filterUpdateAttributes = $this->updateOnlyAttributes || $this->updateExceptAttributes;
        $modelClass = $this->modelClass;
        $dataChunks = array_chunk($data, $this->chunkSize, true);
        foreach ($dataChunks as $chunkedData) {
            $existModels = [];
            if ($this->indexKeys) {
                $conditions = ArrayHelper::getColumn($chunkedData, function ($values) {
                    $array = is_array($values) ? $values : ArrayHelper::toArray($values, [], false);
                    return array_intersect_key($array, array_flip($this->indexKeys));
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
                if ($values instanceof BaseActiveRecord) {
                    if (!$model->getIsNewRecord()) {
                        $values->setIsNewRecord(false);
                        $values->setOldAttributes($model->getOldAttributes());
                    }
                    $models[] = $values;
                    continue;
                }
                if ($model->getIsNewRecord()) {
                    if (!$this->insert) {
                        continue;
                    }
                    if ($filterInsertAttributes) {
                        $values = $this->filterValues($values, $this->insertOnlyAttributes, $this->insertExceptAttributes);
                    }
                } else {
                    if (!$this->update) {
                        continue;
                    }
                    if ($filterUpdateAttributes) {
                        $values = $this->filterValues($values, $this->updateOnlyAttributes, $this->updateExceptAttributes);
                    }
                }
                if ($this->modelScenario) {
                    $model->setScenario($this->modelScenario);
                }
                $model->setAttributes($values);
                if ($this->safeOnly === false) {
                    $model->setAttributes($values, false);
                }
                $models[] = $model;
            }
        }
        return $models;
    }
}
