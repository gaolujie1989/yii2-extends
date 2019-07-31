<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveController
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveController extends \yii\rest\ActiveController
{
    public $idSeparator = ';';

    public $formClass;
    public $searchClass;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->formClass)) {
            $this->formClass = ClassHelper::getFormClass($this->modelClass) ?: $this->modelClass;
        }
        if (empty($this->searchClass)) {
            $this->searchClass = ClassHelper::getSearchClass($this->modelClass) ?: $this->modelClass;
        }
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [
            Yii::createObject([
                'class' => IndexDataProviderPreparer::class,
                'searchModelClass' => $this->searchClass
            ]),
            'prepare'
        ];
        if ($this->formClass) {
            $actions['create']['modelClass'] = $this->formClass;
            $actions['update']['modelClass'] = $this->formClass;
            $actions['delete']['modelClass'] = $this->formClass;
            $actions['view']['modelClass'] = $this->formClass;
        }
        return $actions;
    }

    /**
     * @param $id
     * @return ActiveRecordInterface
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function findModel($id): ActiveRecordInterface
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
        }

        throw new NotFoundHttpException("Model not found: $id");
    }

    /**
     * @param $ids
     * @return ActiveRecordInterface[]
     * @inheritdoc
     */
    public function findModels($ids): array
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        return $modelClass::findAll($this->getIdsCondition($ids));
    }

    /**
     * @param $ids
     * @return array|null
     * @inheritdoc
     */
    protected function getIdsCondition($ids): ?array
    {
        if (empty($ids)) {
            return null;
        }

        if (is_string($ids) && $ids) {
            $ids = explode($this->idSeparator, $ids);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $pkColumns = $modelClass::primaryKey();
        if (count($pkColumns) > 1) {
            $condition = [];
            foreach ($ids as $values) {
                $values = explode(',', $values);
                if (count($pkColumns) === count($values)) {
                    $condition[] = array_combine($pkColumns, $values);
                }
            }
            if ($condition) {
                array_unshift($condition, 'OR');
                return $condition;
            }
        } elseif ($ids !== null) {
            return [$pkColumns[0] => $ids];
        }

        return null;
    }
}
