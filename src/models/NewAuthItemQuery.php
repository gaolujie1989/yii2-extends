<?php

namespace lujie\auth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[NewAuthItem]].
 *
 * @method NewAuthItemQuery id($id)
 * @method NewAuthItemQuery orderById($sort = SORT_ASC)
 * @method NewAuthItemQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method NewAuthItemQuery itemId($itemId)
 * @method NewAuthItemQuery type($type)
 *
 * @method NewAuthItemQuery createdAtBetween($from, $to = null)
 * @method NewAuthItemQuery updatedAtBetween($from, $to = null)
 *
 * @method NewAuthItemQuery orderByItemId($sort = SORT_ASC)
 * @method NewAuthItemQuery orderByCreatedAt($sort = SORT_ASC)
 * @method NewAuthItemQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method NewAuthItemQuery indexByItemId()
 *
 * @method NewAuthItemQuery getItemIds()
 *
 * @method array|NewAuthItem[] all($db = null)
 * @method array|NewAuthItem|null one($db = null)
 * @method array|NewAuthItem[] each($batchSize = 100, $db = null)
 *
 * @see NewAuthItem
 */
class NewAuthItemQuery extends \yii\db\ActiveQuery
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
                    'itemId' => 'item_id',
                    'type' => 'type',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByItemId' => 'item_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByItemId' => 'item_id',
                ],
                'queryReturns' => [
                    'getItemIds' => 'item_id',
                ]
            ]
        ];
    }

}
