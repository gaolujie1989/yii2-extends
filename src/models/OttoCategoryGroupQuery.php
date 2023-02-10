<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OttoCategoryGroup]].
 *
 * @method OttoCategoryGroupQuery id($id)
 * @method OttoCategoryGroupQuery orderById($sort = SORT_ASC)
 * @method OttoCategoryGroupQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method OttoCategoryGroupQuery ottoCategoryGroupId($ottoCategoryGroupId)
 *
 * @method OttoCategoryGroupQuery ottoCreatedAtBetween($from, $to = null)
 * @method OttoCategoryGroupQuery ottoUpdatedAtBetween($from, $to = null)
 * @method OttoCategoryGroupQuery createdAtBetween($from, $to = null)
 * @method OttoCategoryGroupQuery updatedAtBetween($from, $to = null)
 *
 * @method OttoCategoryGroupQuery orderByOttoCategoryGroupId($sort = SORT_ASC)
 * @method OttoCategoryGroupQuery orderByOttoCreatedAt($sort = SORT_ASC)
 * @method OttoCategoryGroupQuery orderByOttoUpdatedAt($sort = SORT_ASC)
 * @method OttoCategoryGroupQuery orderByCreatedAt($sort = SORT_ASC)
 * @method OttoCategoryGroupQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method OttoCategoryGroupQuery indexByOttoCategoryGroupId()
 *
 * @method array getOttoCategoryGroupIds()
 *
 * @method array|OttoCategoryGroup[] all($db = null)
 * @method array|OttoCategoryGroup|null one($db = null)
 * @method array|OttoCategoryGroup[] each($batchSize = 100, $db = null)
 *
 * @see OttoCategoryGroup
 */
class OttoCategoryGroupQuery extends \yii\db\ActiveQuery
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
                    'ottoCategoryGroupId' => 'otto_category_group_id',
                    'ottoCreatedAtBetween' => ['otto_created_at' => 'BETWEEN'],
                    'ottoUpdatedAtBetween' => ['otto_updated_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByOttoCategoryGroupId' => 'otto_category_group_id',
                    'orderByOttoCreatedAt' => 'otto_created_at',
                    'orderByOttoUpdatedAt' => 'otto_updated_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByOttoCategoryGroupId' => 'otto_category_group_id',
                ],
                'queryReturns' => [
                    'getOttoCategoryGroupIds' => ['otto_category_group_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
