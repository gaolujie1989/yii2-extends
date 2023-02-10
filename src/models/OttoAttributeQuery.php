<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OttoAttribute]].
 *
 * @method OttoAttributeQuery id($id)
 * @method OttoAttributeQuery orderById($sort = SORT_ASC)
 * @method OttoAttributeQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method OttoAttributeQuery ottoAttributeId($ottoAttributeId)
 * @method OttoAttributeQuery type($type)
 *
 * @method OttoAttributeQuery createdAtBetween($from, $to = null)
 * @method OttoAttributeQuery updatedAtBetween($from, $to = null)
 *
 * @method OttoAttributeQuery orderByOttoAttributeId($sort = SORT_ASC)
 * @method OttoAttributeQuery orderByCreatedAt($sort = SORT_ASC)
 * @method OttoAttributeQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method OttoAttributeQuery indexByOttoAttributeId()
 *
 * @method array getOttoAttributeIds()
 *
 * @method array|OttoAttribute[] all($db = null)
 * @method array|OttoAttribute|null one($db = null)
 * @method array|OttoAttribute[] each($batchSize = 100, $db = null)
 *
 * @see OttoAttribute
 */
class OttoAttributeQuery extends \yii\db\ActiveQuery
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
                    'ottoAttributeId' => 'otto_attribute_id',
                    'type' => 'type',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByOttoAttributeId' => 'otto_attribute_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByOttoAttributeId' => 'otto_attribute_id',
                ],
                'queryReturns' => [
                    'getOttoAttributeIds' => ['otto_attribute_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
