<?php

namespace lujie\ar\history\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[History]].
 *
 * @method ModelHistoryQuery id($id)
 * @method ModelHistoryQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ModelHistoryQuery modelHistoryId($historyId)
 * @method ModelHistoryQuery modelType($modelType)
 * @method ModelHistoryQuery modelId($modelId)
 * @method ModelHistoryQuery parentId($parentId)
 *
 * @method array|ModelHistory[] all($db = null)
 * @method array|ModelHistory|null one($db = null)
 * @method array|ModelHistory[] each($batchSize = 100, $db = null)
 *
 * @see ModelHistory
 */
class ModelHistoryQuery extends \yii\db\ActiveQuery
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
                    'modelHistoryId' => 'model_history_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'parentId' => 'parent_id',
                ],
            ]
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function clearWhereAppendOnCondition(): ActiveQuery
    {
        /** @var ModelHistory $modelClass */
        $modelClass = $this->modelClass;
        return $this->where([])->andOnCondition(['model_type' => $modelClass::MODEL_TYPE]);
    }
}
