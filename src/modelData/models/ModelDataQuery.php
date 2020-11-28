<?php

namespace lujie\common\modelData\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelData]].
 *
 * @method ModelDataQuery id($id)
 * @method ModelDataQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelDataQuery modelDataId($modelDataId)
 * @method ModelDataQuery modelType($modelType)
 * @method ModelDataQuery modelId($modelId)
 *
 * @method array|ModelData[] all($db = null)
 * @method array|ModelData|null one($db = null)
 * @method array|ModelData[] each($batchSize = 100, $db = null)
 *
 * @see ModelData
 */
class ModelDataQuery extends \yii\db\ActiveQuery
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
                    'modelDataId' => 'model_data_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getDataTexts(): array
    {
        return $this->select(['data_text'])->indexBy('model_id')->column();
    }

}
