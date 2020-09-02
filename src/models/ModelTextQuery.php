<?php

namespace lujie\eav\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelText]].
 *
 * @method ModelTextQuery id($id)
 * @method ModelTextQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelTextQuery modelTextId($modelTextId)
 * @method ModelTextQuery modelType($modelType)
 * @method ModelTextQuery modelId($modelId)
 * @method ModelTextQuery key($key)
 *
 * @method array|ModelText[] all($db = null)
 * @method array|ModelText|null one($db = null)
 * @method array|ModelText[] each($batchSize = 100, $db = null)
 *
 * @see ModelText
 */
class ModelTextQuery extends \yii\db\ActiveQuery
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
