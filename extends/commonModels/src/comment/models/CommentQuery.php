<?php

namespace lujie\common\comment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Comment]].
 *
 * @method CommentQuery id($id)
 * @method CommentQuery orderById($sort = SORT_ASC)
 * @method CommentQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method CommentQuery commentId($commentId)
 * @method CommentQuery modelType($modelType)
 * @method CommentQuery modelId($modelId)
 *
 * @method CommentQuery createdAtBetween($from, $to = null)
 *
 * @method CommentQuery orderByCommentId($sort = SORT_ASC)
 * @method CommentQuery orderByModelId($sort = SORT_ASC)
 * @method CommentQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method CommentQuery indexByCommentId()
 * @method CommentQuery indexByModelId()
 *
 * @method array getCommentIds()
 * @method array getModelIds()
 *
 * @method array|Comment[] all($db = null)
 * @method array|Comment|null one($db = null)
 * @method array|Comment[] each($batchSize = 100, $db = null)
 *
 * @see Comment
 */
class CommentQuery extends \yii\db\ActiveQuery
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
                    'commentId' => 'comment_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByCommentId' => 'comment_id',
                    'orderByModelId' => 'model_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByCommentId' => 'comment_id',
                    'indexByModelId' => 'model_id',
                ],
                'queryReturns' => [
                    'getCommentIds' => ['comment_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelIds' => ['model_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
