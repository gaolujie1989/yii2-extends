<?php

namespace lujie\eav\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelInt]].
 *
 * @method ModelIntQuery id($id)
 * @method ModelIntQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelIntQuery modelTextId($modelTextId)
 * @method ModelIntQuery modelType($modelType)
 * @method ModelIntQuery modelId($modelId)
 * @method ModelIntQuery key($key)
 *
 * @method array|ModelInt[] all($db = null)
 * @method array|ModelInt|null one($db = null)
 * @method array|ModelInt[] each($batchSize = 100, $db = null)
 *
 * @see ModelInt
 */
class ModelIntQuery extends \yii\db\ActiveQuery
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
