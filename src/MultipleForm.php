<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\batch;


use Yii;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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
    public $multiModels;

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function load($data, $formName = null): void
    {
        $modelClass = $this->modelClass;
        $pkColumn = $modelClass::primaryKey()[0];
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
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        $result = Model::validateMultiple($this->multiModels, $attributeNames);
        if (!$result) {
            $this->addErrors(ArrayHelper::getColumn($this->multiModels, 'errors'));
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
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        $callable = function () use ($attributeNames) {
            foreach ($this->multiModels as $model) {
                if ($model->save(false, $attributeNames) === false && !$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
                }
            }
        };

        $db = $this->modelClass::getDb();
        if ($db instanceof Connection) {
            $db->transaction($callable);
        } else {
            $callable();
        }
        return true;
    }

    /**
     * @param $id
     * @return BaseActiveRecord
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function findModel($id): ?BaseActiveRecord
    {
        $model = null;
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

        if ($model === null) {
            throw new NotFoundHttpException("Object not found: $id");
        }
        return $model;
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
        }, $this->multiModels);
    }
}
