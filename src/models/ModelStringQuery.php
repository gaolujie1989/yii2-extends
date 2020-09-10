<?php

namespace lujie\eav\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelString]].
 *
 * @method ModelStringQuery id($id)
 * @method ModelStringQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelStringQuery modelTextId($modelTextId)
 * @method ModelStringQuery modelType($modelType)
 * @method ModelStringQuery modelId($modelId)
 * @method ModelStringQuery key($key)
 *
 * @method array|ModelString[] all($db = null)
 * @method array|ModelString|null one($db = null)
 * @method array|ModelString[] each($batchSize = 100, $db = null)
 *
 * @see ModelString
 */
class ModelStringQuery extends \yii\db\ActiveQuery
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
                    'modelTextId' => 'model_text_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'key' => 'key',
                ]
            ]
        ];
    }

}
