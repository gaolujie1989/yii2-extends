<?php

namespace lujie\common\history\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelHistory]].
 *
 * @method ModelHistoryQuery id($id)
 * @method ModelHistoryQuery orderById($sort = SORT_ASC)
 * @method ModelHistoryQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelHistoryQuery modelHistoryId($modelHistoryId)
 * @method ModelHistoryQuery modelType($modelType)
 * @method ModelHistoryQuery modelId($modelId)
 * @method ModelHistoryQuery modelKey($modelKey, bool|string $like = false)
 * @method ModelHistoryQuery modelParentId($modelParentId)
 *
 * @method ModelHistoryQuery createdAtBetween($from, $to = null)
 *
 * @method ModelHistoryQuery orderByModelHistoryId($sort = SORT_ASC)
 * @method ModelHistoryQuery orderByModelId($sort = SORT_ASC)
 * @method ModelHistoryQuery orderByModelParentId($sort = SORT_ASC)
 * @method ModelHistoryQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method ModelHistoryQuery indexByModelHistoryId()
 * @method ModelHistoryQuery indexByModelId()
 * @method ModelHistoryQuery indexByModelKey()
 * @method ModelHistoryQuery indexByModelParentId()
 *
 * @method array getModelHistoryIds()
 * @method array getModelIds()
 * @method array getModelKeys()
 * @method array getModelParentIds()
 *
 * @method array|ModelHistory[] all($db = null)
 * @method array|ModelHistory|null one($db = null)
 * @method array|ModelHistory[] each($batchSize = 100, $db = null)
 *
 * @see ModelHistory
 */
class ModelHistoryQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'modelHistoryId' => 'model_history_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'modelKey' => 'model_key',
                    'modelParentId' => 'model_parent_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByModelHistoryId' => 'model_history_id',
                    'orderByModelId' => 'model_id',
                    'orderByModelParentId' => 'model_parent_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByModelHistoryId' => 'model_history_id',
                    'indexByModelId' => 'model_id',
                    'indexByModelKey' => 'model_key',
                    'indexByModelParentId' => 'model_parent_id',
                ],
                'queryReturns' => [
                    'getModelHistoryIds' => ['model_history_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelIds' => ['model_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelKeys' => ['model_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelParentIds' => ['model_parent_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
