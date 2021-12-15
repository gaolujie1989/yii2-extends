<?php

namespace lujie\common\option\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Option]].
 *
 * @method OptionQuery id($id)
 * @method OptionQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method OptionQuery optionId($modelOptionId)
 * @method OptionQuery parentId($parentId)
 * @method OptionQuery value($value)
 *
 * @method array getValues()
 *
 * @method array|Option[] all($db = null)
 * @method array|Option|null one($db = null)
 * @method array|Option[] each($batchSize = 100, $db = null)
 *
 * @see Option
 */
class OptionQuery extends \yii\db\ActiveQuery
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
                    'OptionId' => 'option_id',
                    'parentId' => 'parent_id',
                    'value' => 'value',
                ],
                'queryReturns' => [
                    'getValues' => ['values', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }
}
