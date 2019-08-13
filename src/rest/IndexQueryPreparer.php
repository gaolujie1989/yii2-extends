<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\QueryInterface;

/**
 * Class IndexDataProviderPreparer
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IndexQueryPreparer extends BaseObject
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
     * @var array
     */
    public $with = [];

    /**
     * @param string $modelClass
     * @param array $params
     * @return QueryInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function prepare(string $modelClass, array $params): QueryInterface
    {
        $searchClass = $this->searchClass ?: ClassHelper::getSearchClass($modelClass);
        if ($searchClass) {
            /* @var $searchModel BaseActiveRecord */
            $searchModel = Yii::createObject($searchClass);
            if (method_exists($searchModel, $this->queryMethod)) {
                $searchModel->load($params, $this->formName);
                $query = $searchModel->{$this->queryMethod}();
                if ($this->with && $query instanceof ActiveQuery) {
                    $query->with($this->with);
                }
                return $query;
            }
        }

        /* @var $model BaseActiveRecord */
        $model = new $modelClass();
        if (method_exists($model, $this->queryMethod)) {
            $model->load($params, $this->formName);
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
