<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use lujie\extend\base\ModelAttributeTrait;
use lujie\extend\helpers\TransactionHelper;
use lujie\extend\helpers\ValueHelper;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BatchMethodForm
 * @package lujie\core\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchForm extends Model
{
    use ModelAttributeTrait;

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
        $attributes = array_filter($this->getAttributes(), [ValueHelper::class, 'notEmpty']);
        if (empty($attributes)) {
            return true;
        }

        if ($runValidation && !$this->validate()) {
            return false;
        }

        $models = $this->findModels();
        if (empty($models)) {
            return true;
        }

        foreach ($models as $model) {
            $model->setAttributes($attributes);
        }

        if ($this->validateModels && !Model::validateMultiple($models, $attributeNames)) {
            $modelErrors = ArrayHelper::getColumn($models, 'errors');
            $this->addErrors(array_merge(...$modelErrors));
            return false;
        }

        $callable = function () use ($models, $attributeNames) {
            foreach ($models as $model) {
                if ($model->save(false, $attributeNames) === false) {
                    $this->addErrors($model->getErrors());
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

        $callable = function () use ($models) {
            foreach ($models as $model) {
                if ($model->delete() === false) {
                    $this->addErrors($model->getErrors());
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
