<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\data\ActiveArrayDataProvider;
use Yii;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
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
    public $searchClass;

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
     * @var bool
     */
    public $typecast = true;

    /**
     * @var array
     */
    public $dataProviderConfig = [];

    /**
     * @param Action $action
     * @return DataProviderInterface|Model
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
            'searchClass' => $this->searchClass,
            'queryMethod' => $this->queryMethod,
            'formName' => $this->formName,
        ]);
        $query = $queryPreparer->prepare($action->modelClass, $requestParams);
        if ($query === null) {
            return $queryPreparer->searchModel;
        }
        $this->expandQuery($query);

        /** @var DataProviderInterface $object */
        $object = Yii::createObject(array_merge(
            ['class' => ActiveArrayDataProvider::class],
            $this->dataProviderConfig,
            [
                'query' => $query,
                'typecast' => $this->typecast
            ]
        ));
        return $object;
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
        /** @var ActiveQuery $query */
        if ($query instanceof ActiveQueryInterface && $expandFields = $this->getExpandFields()) {
            /** @var BaseActiveRecord $model */
            $model = new $query->modelClass();
            $expandFields = array_filter($expandFields, static function ($expandField) use ($model) {
                return $model->getRelation($expandField, false) !== null;
            });
            $query->with($expandFields);
        }
    }
}
