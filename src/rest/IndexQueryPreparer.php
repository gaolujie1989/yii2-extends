<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\QueryInterface;
use yii\rest\Action;

/**
 * Class IndexDataProviderPreparer
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IndexQueryPreparer
{
    /**
     * @var BaseActiveRecord
     */
    public $searchModelClass;

    /**
     * @var string
     */
    public $queryMethod = 'query';

    /**
     * @var string
     */
    public $formName = '';

    /**
     * @var array
     */
    public $with = [];

    /**
     * @param Action $action
     * @return QueryInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function prepare(Action $action): QueryInterface
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $searchClass = $this->searchModelClass ?: ClassHelper::getSearchClass($action->modelClass);
        if ($searchClass) {
            $searchModel = Yii::createObject($searchClass);
            if (method_exists($searchModel, $this->queryMethod)) {
                $searchModel->load($requestParams, $this->formName);
                $query = $searchModel->{$this->queryMethod}();
                if ($this->with && $query instanceof ActiveQuery) {
                    $query->with($this->with);
                }
                return $query;
            }
        }

        /* @var $model BaseActiveRecord */
        $model = new $action->modelClass();
        if (method_exists($model, $this->queryMethod)) {
            $model->load($requestParams, $this->formName);
            $query = $model->{$this->queryMethod}();
            if ($this->with && $query instanceof ActiveQuery) {
                $query->with($this->with);
            }
            return $query;
        }

        $query = $model::find();
        if ($this->with && $query instanceof ActiveQuery) {
            $query->with($this->with);
        }
        return $query;
    }
}
