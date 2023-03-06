<?php

namespace lujie\ar\deleted\backup\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DeletedData]].
 *
 * @method DeletedDataQuery id($id)
 * @method DeletedDataQuery orderById($sort = SORT_ASC)
 * @method DeletedDataQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method DeletedDataQuery tableName($tableName)
 * @method DeletedDataQuery rowId($rowId)
 * @method DeletedDataQuery rowKey($rowKey, bool $like = false)
 * @method DeletedDataQuery rowParentId($rowParentId)
 *
 * @method DeletedDataQuery createdAtBetween($from, $to = null)
 *
 * @method DeletedDataQuery orderByRowId($sort = SORT_ASC)
 * @method DeletedDataQuery orderByRowParentId($sort = SORT_ASC)
 * @method DeletedDataQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method DeletedDataQuery indexByRowId()
 * @method DeletedDataQuery indexByRowKey()
 * @method DeletedDataQuery indexByRowParentId()
 *
 * @method array getRowIds()
 * @method array getRowKeys()
 * @method array getRowParentIds()
 *
 * @method array|DeletedData[] all($db = null)
 * @method array|DeletedData|null one($db = null)
 * @method array|DeletedData[] each($batchSize = 100, $db = null)
 *
 * @see DeletedData
 */
class DeletedDataQuery extends \yii\db\ActiveQuery
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
                    'rowKey' => 'row_key',
                    'rowParentId' => 'row_parent_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByRowId' => 'row_id',
                    'orderByRowParentId' => 'row_parent_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByRowId' => 'row_id',
                    'indexByRowKey' => 'row_key',
                    'indexByRowParentId' => 'row_parent_id',
                ],
                'queryReturns' => [
                    'getRowIds' => ['row_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getRowKeys' => ['row_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getRowParentIds' => ['row_parent_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
