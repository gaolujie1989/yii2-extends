<?php

namespace lujie\eav\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelInt]].
 *
 * @method ModelValueQuery id($id)
 * @method ModelValueQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelValueQuery modelTextId($modelTextId)
 * @method ModelValueQuery modelType($modelType)
 * @method ModelValueQuery modelId($modelId)
 * @method ModelValueQuery key($key)
 *
 * @method array|ModelInt[]|ModelString[]|ModelText[] all($db = null)
 * @method array|ModelInt|ModelString|ModelText|null one($db = null)
 * @method array|ModelInt[]|ModelString[]|ModelText[] each($batchSize = 100, $db = null)
 *
 * @see ModelInt
 */
class ModelValueQuery extends \yii\db\ActiveQuery
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
