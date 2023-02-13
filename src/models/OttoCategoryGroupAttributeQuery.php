<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OttoCategoryGroupAttribute]].
 *
 * @method OttoCategoryGroupAttributeQuery id($id)
 * @method OttoCategoryGroupAttributeQuery orderById($sort = SORT_ASC)
 * @method OttoCategoryGroupAttributeQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method OttoCategoryGroupAttributeQuery ottoCategoryGroupAttributeId($ottoCategoryGroupAttributeId)
 *
 * @method OttoCategoryGroupAttributeQuery createdAtBetween($from, $to = null)
 * @method OttoCategoryGroupAttributeQuery updatedAtBetween($from, $to = null)
 *
 * @method OttoCategoryGroupAttributeQuery orderByOttoCategoryGroupAttributeId($sort = SORT_ASC)
 * @method OttoCategoryGroupAttributeQuery orderByCreatedAt($sort = SORT_ASC)
 * @method OttoCategoryGroupAttributeQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method OttoCategoryGroupAttributeQuery indexByOttoCategoryGroupAttributeId()
 *
 * @method array getOttoCategoryGroupAttributeIds()
 *
 * @method array|OttoCategoryGroupAttribute[] all($db = null)
 * @method array|OttoCategoryGroupAttribute|null one($db = null)
 * @method array|OttoCategoryGroupAttribute[] each($batchSize = 100, $db = null)
 *
 * @see OttoCategoryGroupAttribute
 */
class OttoCategoryGroupAttributeQuery extends \yii\db\ActiveQuery
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
                    'ottoCategoryGroupAttributeId' => 'otto_category_group_attribute_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByOttoCategoryGroupAttributeId' => 'otto_category_group_attribute_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByOttoCategoryGroupAttributeId' => 'otto_category_group_attribute_id',
                ],
                'queryReturns' => [
                    'getOttoCategoryGroupAttributeIds' => ['otto_category_group_attribute_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
