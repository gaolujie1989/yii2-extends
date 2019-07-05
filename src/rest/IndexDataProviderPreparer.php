<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\db\QueryInterface;
use yii\rest\IndexAction;

/**
 * Class IndexDataProviderPreparer
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IndexDataProviderPreparer
{
    /**
     * @var BaseActiveRecord
     */
    public $searchModelClass;

    /**
     * @var string
     */
    public $searchMethod = 'search';

    /**
     * @var string
     */
    public $queryMethod = 'query';

    /**
     * @var string
     */
    public $formName = '';

    /**
     * @param IndexAction $action
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function prepare(IndexAction $action)
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
                $result = $searchModel->{$this->queryMethod}();
                if ($result instanceof QueryInterface) {
                    return Yii::createObject([
                        'class' => ActiveDataProvider::class,
                        'query' => $result,
                    ]);
                }
                return $result;
            }
        }

        /* @var $model BaseActiveRecord */
        $model = new $action->modelClass();
        if (method_exists($model, $this->queryMethod)) {
            $model->load($requestParams, $this->formName);
            $result = $model->{$this->queryMethod}();
            if ($result instanceof QueryInterface) {
                return Yii::createObject([
                    'class' => ActiveDataProvider::class,
                    'query' => $result,
                ]);
            }
            return $result;
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $model::find(),
        ]);
    }
}
