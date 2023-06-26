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
use yii\validators\Validator;

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
    public $batchCondition;

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
     * @var array
     */
    private $_batchModels;

    /**
     * @var array
     */
    private $_batchAttributes;

    /**
     * @return array|BaseActiveRecord[]
     * @inheritdoc
     */
    public function getBatchModels(): array
    {
        if ($this->_batchModels === null) {
            $this->_batchModels = $this->findModels();
        }
        return $this->_batchModels;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getBatchAttributes(): array
    {
        if ($this->_batchAttributes === null) {
            $this->_batchAttributes = array_filter($this->getAttributes(), [ValueHelper::class, 'notEmpty']);
        }
        return $this->_batchAttributes;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        return array_intersect_key($model->safeAttributes(), $this->safeAttributes());
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
            if (!isset(Validator::$builtInValidators[$ruleName]) || in_array($ruleName, $this->invalidRules, true)) {
                unset($rules[$key]);
            } else if (isset($this->convertRules[$ruleName])) {
                $rules[$key] = [$ruleAttributes, $this->convertRules[$ruleName]];
            }
        }
        return array_merge($rules, [
            [['batchModels', 'batchAttributes'], 'required'],
        ]);
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

        $attributes = $this->getBatchAttributes();
        $models = $this->getBatchModels();
        foreach ($models as $model) {
            $this->setModelAttributes($model, $attributes);
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
     * @param BaseActiveRecord $model
     * @param array $attributes
     * @inheritdoc
     */
    protected function setModelAttributes(BaseActiveRecord $model, array $attributes): void
    {
        $model->setAttributes($attributes, $this->modelSafeOnly);
    }

    /**
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchDelete(): bool
    {
        $models = $this->getBatchModels();
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
        return $this->modelClass::find()->andWhere($this->batchCondition)->all();
    }
}
