<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\history\models;

use lujie\backup\delete\models\DeletedData;
use lujie\backup\delete\models\DeletedDataQuery;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class HistoryQuery
 *
 * @method HistoryQuery id($id)
 * @method HistoryQuery tableName(string $tableName);
 * @method HistoryQuery rowId(int $rowId);
 *
 * @method array|History[] all($db = null)
 * @method array|History|null one($db = null)
 *
 * @see History
 * @package lujie\ar\history\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryQuery extends ActiveQuery
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
                    'tableName' => 'table_name',
                    'rowId' => 'row_id',
                ]
            ],
        ];
    }
}
