<?php

namespace lujie\common\history\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelHistoryDetail]].
 *
 * @method ModelHistoryDetailQuery id($id)
 * @method ModelHistoryDetailQuery orderById($sort = SORT_ASC)
 * @method ModelHistoryDetailQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelHistoryDetailQuery modelHistoryDetailId($modelHistoryDetailId)
 * @method ModelHistoryDetailQuery modelHistoryId($modelHistoryId)
 *
 * @method ModelHistoryDetailQuery createdAtBetween($from, $to = null)
 *
 * @method ModelHistoryDetailQuery orderByModelHistoryDetailId($sort = SORT_ASC)
 * @method ModelHistoryDetailQuery orderByModelHistoryId($sort = SORT_ASC)
 * @method ModelHistoryDetailQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method ModelHistoryDetailQuery indexByModelHistoryDetailId()
 * @method ModelHistoryDetailQuery indexByModelHistoryId()
 *
 * @method array getModelHistoryDetailIds()
 * @method array getModelHistoryIds()
 *
 * @method array|ModelHistoryDetail[] all($db = null)
 * @method array|ModelHistoryDetail|null one($db = null)
 * @method array|ModelHistoryDetail[] each($batchSize = 100, $db = null)
 *
 * @see ModelHistoryDetail
 */
class ModelHistoryDetailQuery extends \yii\db\ActiveQuery
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
                    'modelHistoryDetailId' => 'model_history_detail_id',
                    'modelHistoryId' => 'model_history_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByModelHistoryDetailId' => 'model_history_detail_id',
                    'orderByModelHistoryId' => 'model_history_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByModelHistoryDetailId' => 'model_history_detail_id',
                    'indexByModelHistoryId' => 'model_history_id',
                ],
                'queryReturns' => [
                    'getModelHistoryDetailIds' => ['model_history_detail_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelHistoryIds' => ['model_history_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
