<?php

namespace lujie\common\option\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ModelOption]].
 *
 * @method ModelOptionQuery id($id)
 * @method ModelOptionQuery orderById($sort = SORT_ASC)
 * @method ModelOptionQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelOptionQuery modelOptionId($modelOptionId)
 * @method ModelOptionQuery modelType($modelType)
 * @method ModelOptionQuery modelId($modelId)
 * @method ModelOptionQuery optionId($optionId)
 *
 * @method ModelOptionQuery createdAtBetween($from, $to = null)
 *
 * @method ModelOptionQuery orderByModelOptionId($sort = SORT_ASC)
 * @method ModelOptionQuery orderByModelId($sort = SORT_ASC)
 * @method ModelOptionQuery orderByOptionId($sort = SORT_ASC)
 * @method ModelOptionQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method ModelOptionQuery indexByModelOptionId()
 * @method ModelOptionQuery indexByModelId()
 * @method ModelOptionQuery indexByOptionId()
 *
 * @method array getModelOptionIds()
 * @method array getModelIds()
 * @method array getOptionIds()
 *
 * @method array|ModelOption[] all($db = null)
 * @method array|ModelOption|null one($db = null)
 * @method array|ModelOption[] each($batchSize = 100, $db = null)
 *
 * @see ModelOption
 */
class ModelOptionQuery extends \yii\db\ActiveQuery
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
                    'modelOptionId' => 'model_option_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'optionId' => 'option_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByModelOptionId' => 'model_option_id',
                    'orderByModelId' => 'model_id',
                    'orderByOptionId' => 'option_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByModelOptionId' => 'model_option_id',
                    'indexByModelId' => 'model_id',
                    'indexByOptionId' => 'option_id',
                ],
                'queryReturns' => [
                    'getModelOptionIds' => ['model_option_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelIds' => ['model_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getOptionIds' => ['option_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
