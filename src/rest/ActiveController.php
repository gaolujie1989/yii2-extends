<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ModelHelper;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
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
        $actions['create']['modelClass'] = $this->formClass;
        $actions['update']['modelClass'] = $this->formClass;
        $actions['delete']['modelClass'] = $this->formClass;
        $actions['view']['modelClass'] = $this->formClass;

        $searchModel = new $this->searchClass();
        /** @var IndexDataProviderPreparer $dataProviderPreparer */
        $dataProviderPreparer = Yii::createObject([
            'class' => IndexDataProviderPreparer::class,
            'searchClass' => $this->searchClass,
            'typecast' => $this->indexTypecast,
            'dataProviderConfig' => [
                'totalCount' => method_exists($searchModel, 'getTotalCount') ? $searchModel->getTotalCount() : null,
            ]
        ]);
        $actions['index']['prepareDataProvider'] = [$dataProviderPreparer, 'prepare'];

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

        if ($this->modelClass) {
            /** @var BaseActiveRecord $model */
            $model = new $this->modelClass();
            if ($model->getBehavior('position')) {
                $actions = array_merge($actions, $this->positionActions());
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

    /**
     * @return array[]
     * @inheritdoc
     */
    public function positionActions(): array
    {
        return [
            'move-to' => [
                'class' => MethodAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'moveToPosition'
            ],
            'move-prev' => [
                'class' => MethodAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'movePrev'
            ],
            'move-next' => [
                'class' => MethodAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'moveNext'
            ],
            'move-first' => [
                'class' => MethodAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'moveFirst'
            ],
            'move-last' => [
                'class' => MethodAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'moveLast'
            ],
        ];
    }
}
