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
 * @method OptionQuery optionId($optionId)
 * @method OptionQuery type($type)
 * @method OptionQuery valueType($valueType)
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
                    'optionId' => 'option_id',
                    'type' => 'type',
                    'valueType' => 'value_type',
                ]
            ]
        ];
    }

}
