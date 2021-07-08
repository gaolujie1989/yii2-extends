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
     * @var bool
     */
    public $modelSafeOnly = true;

    /**
     * @var array
     */
    public $invalidRules = [
        'captcha',
        'default',
        'file',
        'image',
        'required',
        'unique',
    ];

    /**
     * @var string[]
     */
    public $convertRules = [
        'date' => 'string',
        'datetime' => 'string',
        'time' => 'string',
        'each' => 'safe',
    ];

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        return $model->safeAttributes();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        $rules = $model->rules();
        foreach ($rules as $key => $ruleConfig) {
            [$ruleAttributes, $ruleName] = $ruleConfig;
            if (in_array($ruleName, $this->invalidRules, true)) {
                unset($rules[$key]);
            } else if (isset($this->convertRules[$ruleName])) {
                $rules[$key] = [$ruleAttributes, $this->convertRules[$ruleName]];
            }
        }
        return $rules;
    }

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

        $attributes = array_filter($this->getAttributes(), [ValueHelper::class, 'notEmpty']);
        if (empty($attributes)) {
            return true;
        }

        $models = $this->findModels();
        if (empty($models)) {
            return true;
        }

        foreach ($models as $model) {
            $model->setAttributes($attributes, $this->modelSafeOnly);
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
