<?php

namespace lujie\auth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[NewAuthItemChild]].
 *
 * @method NewAuthItemChildQuery id($id)
 * @method NewAuthItemChildQuery orderById($sort = SORT_ASC)
 * @method NewAuthItemChildQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method NewAuthItemChildQuery itemChildId($itemChildId)
 *
 * @method NewAuthItemChildQuery createdAtBetween($from, $to = null)
 *
 * @method NewAuthItemChildQuery orderByItemChildId($sort = SORT_ASC)
 * @method NewAuthItemChildQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method NewAuthItemChildQuery indexByItemChildId()
 *
 * @method NewAuthItemChildQuery getItemChildIds()
 *
 * @method array|NewAuthItemChild[] all($db = null)
 * @method array|NewAuthItemChild|null one($db = null)
 * @method array|NewAuthItemChild[] each($batchSize = 100, $db = null)
 *
 * @see NewAuthItemChild
 */
class NewAuthItemChildQuery extends \yii\db\ActiveQuery
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
                    'itemChildId' => 'item_child_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByItemChildId' => 'item_child_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByItemChildId' => 'item_child_id',
                ],
                'queryReturns' => [
                    'getItemChildIds' => 'item_child_id',
                ]
            ]
        ];
    }

}
