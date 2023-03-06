<?php

namespace lujie\common\category\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ExternalCategory]].
 *
 * @method ExternalCategoryQuery id($id)
 * @method ExternalCategoryQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ExternalCategoryQuery externalType($externalType)
 * @method ExternalCategoryQuery categoryId($categoryId)
 * @method ExternalCategoryQuery parentId($parentId)
 *
 * @method array|ExternalCategory[] all($db = null)
 * @method array|ExternalCategory|null one($db = null)
 * @method array|ExternalCategory[] each($batchSize = 100, $db = null)
 *
 * @see ExternalCategory
 */
class ExternalCategoryQuery extends \yii\db\ActiveQuery
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
                    'externalType' => 'external_type',
                    'categoryId' => 'category_id',
                    'parentId' => 'parent_id',
                ]
            ]
        ];
    }

}
