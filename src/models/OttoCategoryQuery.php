<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OttoCategory]].
 *
 * @method OttoCategoryQuery id($id)
 * @method OttoCategoryQuery orderById($sort = SORT_ASC)
 * @method OttoCategoryQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method OttoCategoryQuery ottoCategoryId($ottoCategoryId)
 *
 * @method OttoCategoryQuery ottoCreatedAtBetween($from, $to = null)
 * @method OttoCategoryQuery ottoUpdatedAtBetween($from, $to = null)
 * @method OttoCategoryQuery createdAtBetween($from, $to = null)
 * @method OttoCategoryQuery updatedAtBetween($from, $to = null)
 *
 * @method OttoCategoryQuery orderByOttoCategoryId($sort = SORT_ASC)
 * @method OttoCategoryQuery orderByOttoCreatedAt($sort = SORT_ASC)
 * @method OttoCategoryQuery orderByOttoUpdatedAt($sort = SORT_ASC)
 * @method OttoCategoryQuery orderByCreatedAt($sort = SORT_ASC)
 * @method OttoCategoryQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method OttoCategoryQuery indexByOttoCategoryId()
 *
 * @method array getOttoCategoryIds()
 *
 * @method array|OttoCategory[] all($db = null)
 * @method array|OttoCategory|null one($db = null)
 * @method array|OttoCategory[] each($batchSize = 100, $db = null)
 *
 * @see OttoCategory
 */
class OttoCategoryQuery extends \yii\db\ActiveQuery
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
                    'ottoCategoryId' => 'otto_category_id',
                    'ottoCreatedAtBetween' => ['otto_created_at' => 'BETWEEN'],
                    'ottoUpdatedAtBetween' => ['otto_updated_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByOttoCategoryId' => 'otto_category_id',
                    'orderByOttoCreatedAt' => 'otto_created_at',
                    'orderByOttoUpdatedAt' => 'otto_updated_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByOttoCategoryId' => 'otto_category_id',
                ],
                'queryReturns' => [
                    'getOttoCategoryIds' => ['otto_category_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
