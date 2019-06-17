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
 * @method DeletedDataQuery tableName(string $tableName);
 * @method DeletedDataQuery rowId(int $rowId);
 *
 * @method DeletedData|array one($db = null)
 * @method DeletedData[]|array all($db = null)
 *
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
