<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\batch;


use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class BatchSaveForm
 * @package lujie\core\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MultipleForm extends Model
{
    /** @var ActiveRecordInterface */
    public $modelClass;

    /** @var BaseActiveRecord[] */
    public $multiModels;

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $modelClass = $this->modelClass;
        $pkColumn = reset($modelClass::primaryKey());
        $this->multiModels = [];
        foreach ($data as $values) {
            $pkValue = $values[$pkColumn] ?? ($values['id'] ?? null);
            $this->multiModels[] = $pkValue ? $this->findModel($pkValue) : new $modelClass();
        }
        return Model::loadMultiple($this->multiModels, $data, $formName);
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        $result = Model::validateMultiple($this->multiModels, $attributeNames);
        $this->addErrors(ArrayHelper::getValue($this->multiModels, 'errors'));

        return $result;
    }

    public function save($runValidation = true, $attributeNames = null, $throwException = true)
    {

    }

    public function __call($name, $params)
    {
        if (strpos($name, 'get') === 0 && $this->multiModels) {
            $model = reset($this->multiModels);
            if ($model->hasMethod($name)) {
                $key = substr($name, 3);
                return ArrayHelper::getValue($this->multiModels, $key);
            }
        }
        return parent::__call($name, $params);
    }

    public function hasErrors($attribute = null)
    {
        foreach ($this->multiModels as $model) {
            if ($model->hasErrors($attribute)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return ActiveRecordInterface
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function findModel($id)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return ArrayHelper::toArray($this->multiModels, $fields, $recursive);
    }
}
