<?php

namespace lujie\ar\history\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[History]].
 *
 * @method HistoryQuery id($id)
 * @method HistoryQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method HistoryQuery historyId($historyId)
 * @method HistoryQuery modelType($modelType)
 * @method HistoryQuery modelId($modelId)
 * @method HistoryQuery parentId($parentId)
 *
 * @method array|History[] all($db = null)
 * @method array|History|null one($db = null)
 * @method array|History[] each($batchSize = 100, $db = null)
 *
 * @see History
 */
class HistoryQuery extends \yii\db\ActiveQuery
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
                    'historyId' => 'history_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'parentId' => 'parent_id',
                ]
            ]
        ];
    }

}
