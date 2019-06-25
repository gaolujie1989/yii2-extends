<?php

namespace lujie\data\center\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataRecordQuery dataAccountId($dataAccountId)
 * @method DataRecordQuery dataSourceId($dataSourceId)
 * @method DataRecordQuery dataType($dataType)
 * @method DataRecordQuery dataId($dataId)
 * @method DataRecordQuery dataKey($dataKey)
 * @method DataRecordQuery dataParentId($dataParentId)
 * @method DataRecordQuery dataUpdatedAtFrom($dataUpdatedAtFrom)
 * @method DataRecordQuery dataUpdatedAtTo($dataUpdatedAtTo)
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
                    'dataAccountId' => 'data_account_id',
                    'dataSourceId' => 'data_source_id',
                    'dataType' => 'data_type',
                    'dataId' => 'data_id',
                    'dataKey' => 'data_key',
                    'dataParentId' => 'data_parent_id',
                    'dataUpdatedAtFrom' => ['>=', 'data_updated_at'],
                    'dataUpdatedAtTo' => ['<=', 'data_updated_at'],
                ],
            ]
        ];
    }
}
