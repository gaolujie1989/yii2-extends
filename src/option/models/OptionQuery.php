<?php

namespace lujie\common\option\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelOption]].
 *
 * @method OptionQuery id($id)
 * @method OptionQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method OptionQuery optionId($modelOptionId)
 * @method OptionQuery parentId($parentId)
 * @method OptionQuery key($key)
 *
 * @method array getKeys()
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
                    'key' => 'key',
                ],
                'queryReturns' => [
                    'getKeys' => ['key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
