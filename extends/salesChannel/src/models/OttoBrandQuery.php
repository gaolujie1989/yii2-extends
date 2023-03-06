<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[OttoBrand]].
 *
 * @method OttoBrandQuery id($id)
 * @method OttoBrandQuery orderById($sort = SORT_ASC)
 * @method OttoBrandQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method OttoBrandQuery ottoBrandId($ottoBrandId)
 * @method OttoBrandQuery key($key)
 *
 * @method OttoBrandQuery createdAtBetween($from, $to = null)
 * @method OttoBrandQuery updatedAtBetween($from, $to = null)
 *
 * @method OttoBrandQuery orderByOttoBrandId($sort = SORT_ASC)
 * @method OttoBrandQuery orderByCreatedAt($sort = SORT_ASC)
 * @method OttoBrandQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method OttoBrandQuery indexByOttoBrandId()
 * @method OttoBrandQuery indexByKey()
 *
 * @method array getOttoBrandIds()
 * @method array getKeys()
 *
 * @method array|OttoBrand[] all($db = null)
 * @method array|OttoBrand|null one($db = null)
 * @method array|OttoBrand[] each($batchSize = 100, $db = null)
 *
 * @see OttoBrand
 */
class OttoBrandQuery extends \yii\db\ActiveQuery
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
                    'ottoBrandId' => 'otto_brand_id',
                    'key' => 'key',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByOttoBrandId' => 'otto_brand_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByOttoBrandId' => 'otto_brand_id',
                    'indexByKey' => 'key',
                ],
                'queryReturns' => [
                    'getOttoBrandIds' => ['otto_brand_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getKeys' => ['key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
