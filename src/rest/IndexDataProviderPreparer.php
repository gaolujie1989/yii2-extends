<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

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

        $queryPreparer = new IndexQueryPreparer([
            'searchModelClass' => $this->searchModelClass,
            'queryMethod' => $this->queryMethod,
            'formName' => $this->formName,
        ]);
        $query = $queryPreparer->prepare($action->modelClass, $requestParams);
        $this->expandQuery($query);
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
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
                return $model->getRelation($expandField, false);
            });
            $query->with($expandFields);
        }
    }
}
