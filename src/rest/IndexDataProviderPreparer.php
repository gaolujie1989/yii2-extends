<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\QueryInterface;
use yii\rest\Action;

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
    public $queryMethod = 'query';

    /**
     * @var string
     */
    public $formName = '';

    /**
     * @var string
     */
    public $expandParam = 'expand';

    /**
     * @param Action $action
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function prepare(Action $action)
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
                    $this->expandQuery($result);
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
                $this->expandQuery($result);
                return Yii::createObject([
                    'class' => ActiveDataProvider::class,
                    'query' => $result,
                ]);
            }
            return $result;
        }

        $activeQuery = $model::find();
        $this->expandQuery($activeQuery);
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $activeQuery,
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function getExpandFields(): array
    {
        $expand = Yii::$app->getRequest()->get($this->expandParam);

        return is_string($expand) ? preg_split('/\s*,\s*/', $expand, -1, PREG_SPLIT_NO_EMPTY) : [];
    }

    /**
     * @param QueryInterface $query
     * @inheritdoc
     */
    protected function expandQuery(QueryInterface $query): void
    {
        if ($query instanceof ActiveQuery && $expandFields = $this->getExpandFields()) {
            /** @var BaseActiveRecord $model */
            $model = new $query->modelClass();
            $expandFields = array_filter($expandFields, static function ($expandField) use ($model) {
                $model->getRelation($expandField, false);
            });
            $query->with($expandFields);
        }
    }
}
