<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;


use yii\db\BaseActiveRecord;
use yii\db\Connection;

/**
 * Class ActiveRecordImporter
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordPipeline extends BasePipeline
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
     * @param array $data
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $this->errors = [];
        $this->affectedRowCounts = [];

        /** @var BaseActiveRecord[] $models */
        $models = [];
        $modelClass = $this->modelClass;
        foreach ($data as $key => $values) {
            /** @var BaseActiveRecord $model */
            if ($this->indexKeys) {
                $condition = array_intersect_key($values, array_flip($this->indexKeys));
                $model = $modelClass::findOne($condition) ?: new $modelClass($condition);
            } else {
                $model = new $modelClass();
            }
            if ($this->modelScenario) {
                $model->setScenario($this->modelScenario);
            }
            $model->setAttributes($values);
            $models[$key] = $model;
        }

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
            $counts = [
                self::AFFECTED_CREATED => 0,
                self::AFFECTED_UPDATED => 0,
                self::AFFECTED_SKIPPED => 0
            ];
            foreach ($models as $model) {
                $createOrUpdate = $model->getIsNewRecord() ? self::AFFECTED_CREATED : self::AFFECTED_UPDATED;
                if (empty($model->getDirtyAttributes())) {
                    $counts[self::AFFECTED_SKIPPED]++;
                } else if ($model->save(false)) {
                    $counts[$createOrUpdate]++;
                } else {
                    $counts[self::AFFECTED_SKIPPED]++;
                }
            }
            $this->affectedRowCounts = $counts;
            return true;
        };

        $db = $modelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        }
        return $callable();
    }
}
