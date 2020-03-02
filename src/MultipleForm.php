<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\batch;


use lujie\extend\helpers\TransactionHelper;
use Yii;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BatchSaveForm
 * @package lujie\core\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MultipleForm extends Model
{
    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var BaseActiveRecord[]
     */
    private $loadedModels;

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     * @inheritdoc
     */
    public function load($data, $formName = null): bool
    {
        $this->loadedModels = [];
        foreach ($data as $values) {
            /** @var BaseActiveRecord $model */
            $model = $this->findModel($values) ?: new $this->modelClass();
            $this->loadedModels[] = $model;
        }
        return Model::loadMultiple($this->loadedModels, $data, $formName);
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        $result = Model::validateMultiple($this->loadedModels, $attributeNames);
        if (!$result) {
            $this->addErrors(ArrayHelper::getColumn($this->loadedModels, 'errors'));
        }

        return !$this->hasErrors();
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            Yii::info('Model not save due to validation error.', __METHOD__);
            return false;
        }

        $callable = function () use ($attributeNames) {
            foreach ($this->loadedModels as $model) {
                if ($model->save(false, $attributeNames) === false) {
                    return false;
                }
            }
            return true;
        };

        return TransactionHelper::transaction($callable, $this->modelClass::getDb());
    }

    /**
     * @param array $data
     * @return BaseActiveRecord|null
     * @inheritdoc
     */
    public function findModel(array $data): ?BaseActiveRecord
    {
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        $pkValues = array_intersect_key($data, array_flip($keys));
        if ($pkValues) {
            return $modelClass::findOne($pkValues);
        }
        return null;
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return array_map(static function ($model) use ($fields, $expand, $recursive) {
            /** @var BaseActiveRecord $model */
            $model->toArray($fields, $expand, $recursive);
        }, $this->loadedModels);
    }
}
