<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;


use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class BatchMethodForm
 * @package lujie\core\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchMethodForm extends Model
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
     * @var array the batch update attributes
     */
    private $_attributes = [];

    /**
     * @var callable findModels callback, if set use this to load models
     */
    public $findModels;

    /**
     * @var callable access checker for each model
     */
    public $checkAccess;

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        $model->setScenario($this->getScenario());
        $safeAttributes = $model->safeAttributes();
        if (in_array($name, $safeAttributes)) {
            if (!$this->isEmpty($value)) {
                $this->_attributes[$name] = $value;
            }
            return;
        }

        parent::__set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        $model->setScenario($this->getScenario());
        $safeAttributes = $model->safeAttributes();
        if (in_array($name, $safeAttributes)) {
            return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool|mixed|BaseActiveRecord[]|null
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if ($this->hasBatchMethod($name)) {
            return $this->batchMethod(substr($name, 5));
        }
        return parent::__call($name, $params);
    }

    /**
     * @param string $name
     * @param bool $checkBehaviors
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name, $checkBehaviors = true)
    {
        if ($this->hasBatchMethod($name)) {
            return true;
        }
        return parent::hasMethod($name, $checkBehaviors);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function scenarios()
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        return $model->scenarios();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules()
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass();
        $model->setScenario($this->getScenario());
        return $model->rules();
    }

    /**
     * @param $value
     * @return bool
     * @inheritdoc
     */
    public function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    public function hasBatchMethod($name)
    {
        if (substr($name, 0, 5) == 'batch') {
            $method = lcfirst(substr($name, 5));
            /** @var BaseActiveRecord $model */
            $model = new $this->modelClass();
            if ($model->hasMethod($method)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed|BaseActiveRecord[]|null
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function findModels()
    {
        if (!$this->condition) {
            return null;
        }

        if ($this->findModels !== null) {
            $models = call_user_func($this->findModels, $this->condition, $this);
        } else {
            /** @var BaseActiveRecord $modelClass */
            $modelClass = $this->modelClass;
            /** @var BaseActiveRecord[] $models */
            $models = $modelClass::findAll($this->condition);
        }

        if (!$models) {
            throw new NotFoundHttpException('Object not found: ' . json_encode($this->condition));
        }

        return $models;
    }

    /**
     * @return array|bool|mixed|BaseActiveRecord[]
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchUpdate()
    {
        if (empty($this->_attributes)) {
            return false;
        }

        if (($models = $this->findModels()) === null) {
            return false;
        }

        foreach ($models as $model) {
            if ($this->checkAccess) {
                call_user_func($this->checkAccess, $model);
            }

            $model->setScenario($this->getScenario());
            $model->load($this->_attributes, '');
            if (!$model->validate(array_keys($this->_attributes))) {
                $this->addErrors($model->getErrors());
            }
        }

        if ($this->hasErrors()) {
            return false;
        }

        $callable = function () use ($models) {
            foreach ($models as $model) {
                if ($model->save(false) === false && !$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
                }
            }
        };

        $db = $this->modelClass::getDb();
        if ($db instanceof Connection) {
            $db->transaction($callable);
        } else {
            call_user_func($callable);
        }

        return $models;
    }

    /**
     * @return bool|mixed|BaseActiveRecord[]|null
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchDelete()
    {
        return $this->batchMethod('delete');
    }

    /**
     * @param $method
     * @return bool|mixed|BaseActiveRecord[]|null
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @inheritdoc
     */
    public function batchMethod($method)
    {
        if (($models = $this->findModels()) === false) {
            return false;
        }

        /** @var BaseActiveRecord $modelInstance */
        $modelInstance = new $this->modelClass();
        if (!$modelInstance->hasMethod($method)) {
            return false;
        }

        $callable = function () use ($models, $method) {
            foreach ($models as $model) {
                if ($this->checkAccess) {
                    call_user_func($this->checkAccess, $model);
                }

                call_user_func([$model, $method]);
            }
        };

        $db = $this->modelClass::getDb();
        if ($db instanceof Connection) {
            $db->transaction($callable);
        } else {
            call_user_func($callable);
        }

        return $models;
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        if ($fields) {
            return array_diff_key($this->_attributes, array_flip($fields));
        }
        return $this->_attributes;
    }
}
