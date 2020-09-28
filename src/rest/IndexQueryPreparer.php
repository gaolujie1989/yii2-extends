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
use yii\db\ActiveQueryInterface;
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
     * @var BaseActiveRecord|string|null
     */
    public $searchClass;

    /**
     * @var string
     */
    public $queryMethod = 'query';

    /**
     * @var bool
     */
    public $runValidation = true;

    /**
     * @var string
     */
    public $formName = '';

    /**
     * @var array
     */
    public $with = [];

    /**
     * @var null|bool
     */
    public $asArray = null;

    /**
     * @param string $modelClass
     * @param array $params
     * @return QueryInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function prepare(string $modelClass, array $params): QueryInterface
    {
        /** @var BaseActiveRecord $modelClass */
        $searchClass = $this->searchClass ?: ClassHelper::getSearchClass($modelClass) ?: $modelClass;
        /* @var $searchModel BaseActiveRecord */
        $searchModel = Yii::createObject($searchClass);
        if (method_exists($searchModel, $this->queryMethod)) {
            $searchModel->load($params, $this->formName);
            if ($this->runValidation && !$searchModel->validate()) {
                return $searchModel::find()->where('1=2');
            }
            $query = $searchModel->{$this->queryMethod}();
            $this->appendQuery($query);
            return $query;
        }

        $query = $modelClass::find();
        $this->appendQuery($query);
        return $query;
    }

    /**
     * @param ActiveQueryInterface|ActiveQuery $query
     * @inheritdoc
     */
    protected function appendQuery(ActiveQueryInterface $query): void
    {
        if ($this->with) {
            $query->with($this->with);
        }
        if ($this->asArray !== null) {
            $query->asArray($this->asArray);
        }
    }
}
