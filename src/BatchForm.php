<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use lujie\extend\helpers\TransactionHelper;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BatchMethodForm
 * @package lujie\core\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BatchForm extends Model
{
    /**
     * @var ActiveRecordInterface
     */
    public $modelClass;

    /**
     * @var array the condition to load models
     */
    public $condition;

    /**
     * @var bool
     */
    public $validateModels = false;

    /**
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchUpdate(bool $runValidation = true, ?array $attributeNames = null): bool
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        $models = $this->findModels();
        if (empty($models)) {
            return true;
        }

        foreach ($models as $model) {
            $model->setAttributes($this->getAttributes());
        }

        if ($this->validateModels && !Model::validateMultiple($models, $attributeNames)) {
            $modelErrors = ArrayHelper::getColumn($models, 'errors');
            $this->addErrors(array_merge(...$modelErrors));
            return false;
        }

        $callable = static function () use ($models, $attributeNames) {
            foreach ($models as $model) {
                if ($model->save(false, $attributeNames) === false) {
                    return false;
                }
            }
            return true;
        };
        return TransactionHelper::transaction($callable, $this->modelClass::getDb());
    }

    /**
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchDelete(): bool
    {
        $models = $this->findModels();
        if (empty($models)) {
            return true;
        }

        $callable = static function () use ($models) {
            foreach ($models as $model) {
                if ($model->delete() === false) {
                    return false;
                }
            }
            return true;
        };
        return TransactionHelper::transaction($callable, $this->modelClass::getDb());
    }

    /**
     * @return array|BaseActiveRecord[]
     * @inheritdoc
     */
    protected function findModels(): array
    {
        return $this->modelClass::find()->andWhere($this->condition)->all();
    }
}
