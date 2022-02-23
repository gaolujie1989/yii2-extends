<?php

namespace lujie\auth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[NewAuthAssignment]].
 *
 * @method NewAuthAssignmentQuery id($id)
 * @method NewAuthAssignmentQuery orderById($sort = SORT_ASC)
 * @method NewAuthAssignmentQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method NewAuthAssignmentQuery assignmentId($assignmentId)
 * @method NewAuthAssignmentQuery userId($userId)
 *
 * @method NewAuthAssignmentQuery createdAtBetween($from, $to = null)
 *
 * @method NewAuthAssignmentQuery orderByAssignmentId($sort = SORT_ASC)
 * @method NewAuthAssignmentQuery orderByUserId($sort = SORT_ASC)
 * @method NewAuthAssignmentQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method NewAuthAssignmentQuery indexByAssignmentId()
 * @method NewAuthAssignmentQuery indexByUserId()
 *
 * @method NewAuthAssignmentQuery getAssignmentIds()
 * @method NewAuthAssignmentQuery getUserIds()
 *
 * @method array|NewAuthAssignment[] all($db = null)
 * @method array|NewAuthAssignment|null one($db = null)
 * @method array|NewAuthAssignment[] each($batchSize = 100, $db = null)
 *
 * @see NewAuthAssignment
 */
class NewAuthAssignmentQuery extends \yii\db\ActiveQuery
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
                    'assignmentId' => 'assignment_id',
                    'userId' => 'user_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByAssignmentId' => 'assignment_id',
                    'orderByUserId' => 'user_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByAssignmentId' => 'assignment_id',
                    'indexByUserId' => 'user_id',
                ],
                'queryReturns' => [
                    'getAssignmentIds' => 'assignment_id',
                    'getUserIds' => 'user_id',
                ]
            ]
        ];
    }

}
