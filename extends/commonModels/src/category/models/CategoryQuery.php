<?php

namespace lujie\common\category\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @method CategoryQuery id($id)
 * @method CategoryQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method CategoryQuery categoryId($categoryId)
 * @method CategoryQuery parentId($parentId)
 *
 * @method array|Category[] all($db = null)
 * @method array|Category|null one($db = null)
 * @method array|Category[] each($batchSize = 100, $db = null)
 *
 * @see Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
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
                    'categoryId' => 'category_id',
                    'parentId' => 'parent_id',
                ]
            ]
        ];
    }

}
