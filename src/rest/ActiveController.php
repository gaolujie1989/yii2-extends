<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ModelHelper;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveController
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveController extends \yii\rest\ActiveController
{
    /**
     * @var string
     */
    public $idSeparator = ';';

    /**
     * @var string
     */
    public $formClass;

    /**
     * @var string
     */
    public $searchClass;

    /**
     * @var bool
     */
    public $indexTypecast = true;

    /**
     * @var array ['action' => 'method']
     */
    public $statisticsActions = [
        'total' => 'queryTotal',
        'statistics' => 'queryStatistics'
    ];

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
        $dataProviderPreparer = Yii::createObject([
            'class' => IndexDataProviderPreparer::class,
            'searchClass' => $this->searchClass,
            'typecast' => $this->indexTypecast,
        ]);
        $actions['index']['prepareDataProvider'] = [$dataProviderPreparer, 'prepare'];
        if ($this->formClass) {
            $actions['create']['modelClass'] = $this->formClass;
            $actions['update']['modelClass'] = $this->formClass;
            $actions['delete']['modelClass'] = $this->formClass;
            $actions['view']['modelClass'] = $this->formClass;
        }
        if ($this->searchClass) {
            $searchModel = new $this->searchClass();
            foreach ($this->statisticsActions as $actionName => $methodName) {
                if (method_exists($searchModel, $methodName)) {
                    $totalProviderPreparer = Yii::createObject([
                        'class' => IndexDataProviderPreparer::class,
                        'searchClass' => $this->searchClass,
                        'typecast' => $this->indexTypecast,
                        'queryMethod' => $methodName,
                        'expandParam' => false,
                        'dataProviderConfig' => [
                            'pagination' => false,
                            'sort' => false,
                        ]
                    ]);
                    $actions[$actionName] = [
                        'class' => IndexAction::class,
                        'modelClass' => $this->modelClass,
                        'checkAccess' => [$this, 'checkAccess'],
                        'prepareDataProvider' => [$totalProviderPreparer, 'prepare'],
                    ];
                }
            }
        }
        return $actions;
    }

    /**
     * @param int|string $id
     * @return ActiveRecordInterface
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function findModel($id): ActiveRecordInterface
    {
        $model = ModelHelper::findModel($id, $this->modelClass);
        if ($model === null) {
            throw new NotFoundHttpException("Model not found: $id");
        }
        return $model;
    }

    /**
     * @param array|string|int $ids
     * @param array $with
     * @return ActiveRecordInterface[]
     * @inheritdoc
     */
    public function findModels($ids, $with = []): array
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $condition = ModelHelper::getCondition($ids, $this->modelClass, $this->idSeparator);
        $query = $modelClass::find()->andWhere($condition);
        if ($with) {
            $query->with($with);
        }
        return $query->all();
    }
}
