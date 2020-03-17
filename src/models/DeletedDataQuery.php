<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\backup\delete\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class DeletedDataQuery
 *
 * @method DeletedDataQuery id($id)
 * @method DeletedDataQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DeletedDataQuery tableName($tableName);
 * @method DeletedDataQuery rowId($rowId);
 *
 * @method array|DeletedData[] all($db = null)
 * @method array|DeletedData|null one($db = null)
 * @method array|DeletedData[] each($batchSize = 100, $db = null)
 *
 * @see DeletedData
 * @package lujie\backupdelete\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedDataQuery extends ActiveQuery
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
