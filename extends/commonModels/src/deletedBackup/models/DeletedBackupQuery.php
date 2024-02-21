<?php

namespace lujie\common\deleted\backup\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DeletedBackup]].
 *
 * @method DeletedBackupQuery id($id)
 * @method DeletedBackupQuery orderById($sort = SORT_ASC)
 * @method DeletedBackupQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method DeletedBackupQuery deletedBackupId($deletedBackupId)
 * @method DeletedBackupQuery modelType($modelType)
 * @method DeletedBackupQuery rowId($rowId)
 * @method DeletedBackupQuery rowKey($rowKey, bool|string $like = false)
 * @method DeletedBackupQuery rowParentId($rowParentId)
 *
 * @method DeletedBackupQuery createdAtBetween($from, $to = null)
 *
 * @method DeletedBackupQuery orderByDeletedBackupId($sort = SORT_ASC)
 * @method DeletedBackupQuery orderByRowId($sort = SORT_ASC)
 * @method DeletedBackupQuery orderByRowParentId($sort = SORT_ASC)
 * @method DeletedBackupQuery orderByCreatedAt($sort = SORT_ASC)
 *
 * @method DeletedBackupQuery indexByDeletedBackupId()
 * @method DeletedBackupQuery indexByRowId()
 * @method DeletedBackupQuery indexByRowKey()
 * @method DeletedBackupQuery indexByRowParentId()
 *
 * @method array getDeletedBackupIds()
 * @method array getRowIds()
 * @method array getRowKeys()
 * @method array getRowParentIds()
 *
 * @method array|DeletedBackup[] all($db = null)
 * @method array|DeletedBackup|null one($db = null)
 * @method array|DeletedBackup[] each($batchSize = 100, $db = null)
 *
 * @see DeletedBackup
 */
class DeletedBackupQuery extends \yii\db\ActiveQuery
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
                    'deletedBackupId' => 'deleted_backup_id',
                    'modelType' => 'model_type',
                    'rowId' => 'row_id',
                    'rowKey' => 'row_key',
                    'rowParentId' => 'row_parent_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByDeletedBackupId' => 'deleted_backup_id',
                    'orderByRowId' => 'row_id',
                    'orderByRowParentId' => 'row_parent_id',
                    'orderByCreatedAt' => 'created_at',
                ],
                'queryIndexes' => [
                    'indexByDeletedBackupId' => 'deleted_backup_id',
                    'indexByRowId' => 'row_id',
                    'indexByRowKey' => 'row_key',
                    'indexByRowParentId' => 'row_parent_id',
                ],
                'queryReturns' => [
                    'getDeletedBackupIds' => ['deleted_backup_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getRowIds' => ['row_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getRowKeys' => ['row_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getRowParentIds' => ['row_parent_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
