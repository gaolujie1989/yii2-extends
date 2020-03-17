<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataRecordQuery id($id)
 * @method DataRecordQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DataRecordQuery dataRecordId($dataAccountId)
 * @method DataRecordQuery dataAccountId($dataAccountId)
 * @method DataRecordQuery dataSourceType($dataSourceType)
 * @method DataRecordQuery dataId($dataId)
 * @method DataRecordQuery dataKey($dataKey)
 * @method DataRecordQuery dataParentId($dataParentId)
 * @method DataRecordQuery dataUpdatedAtFrom($dataUpdatedAtFrom)
 * @method DataRecordQuery dataUpdatedAtTo($dataUpdatedAtTo)
 *
 * @method array getDataIds()
 *
 * @method array|DataRecord[] all($db = null)
 * @method array|DataRecord|null one($db = null)
 * @method array|DataRecord[] each($batchSize = 100, $db = null)
 *
 * @see DataRecord
 */
class DataRecordQuery extends \yii\db\ActiveQuery
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
                    'dataRecordId' => 'data_record_id',
                    'dataAccountId' => 'data_account_id',
                    'dataSourceType' => 'data_source_type',
                    'dataId' => 'data_id',
                    'dataKey' => 'data_key',
                    'dataParentId' => 'data_parent_id',
                    'dataUpdatedAtFrom' => ['data_updated_at' => '>='],
                    'dataUpdatedAtTo' => ['data_updated_at' => '<='],
                ],
                'queryReturns' => [
                    'getDataIds' => ['data_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }
}
