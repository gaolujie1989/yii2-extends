<?php

namespace lujie\common\category\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[CategoryLink]].
 *
 * @method CategoryLinkQuery id($id)
 * @method CategoryLinkQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method CategoryLinkQuery categoryLinkId($categoryLinkId)
 * @method CategoryLinkQuery categoryId($categoryId)
 * @method CategoryLinkQuery externalType($externalType)
 * @method CategoryLinkQuery externalCategoryId($externalCategoryId)
 *
 * @method array|CategoryLink[] all($db = null)
 * @method array|CategoryLink|null one($db = null)
 * @method array|CategoryLink[] each($batchSize = 100, $db = null)
 *
 * @see CategoryLink
 */
class CategoryLinkQuery extends \yii\db\ActiveQuery
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
                    'categoryLinkId' => 'category_link_id',
                    'categoryId' => 'category_id',
                    'externalType' => 'external_type',
                    'externalCategoryId' => 'external_category_id',
                ]
            ]
        ];
    }

}
