<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataSourceQuery id($id)
 * @method DataSourceQuery dataAccountId($dataAccountId)
 * @method DataSourceQuery type($type)
 * @method DataSourceQuery active()
 * @method DataSourceQuery pending()
 *
 * @method array|DataSource[] all($db = null)
 * @method array|DataSource|null one($db = null)
 *
 * @see DataSource
 */
class DataSourceQuery extends \yii\db\ActiveQuery
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
                    'type' => 'type',
                ],
                'queryConditions' => [
                    'active' => ['status' => DataSource::STATUS_ACTIVE],
                    'pending' => ['last_exec_status' => DataSource::EXEC_STATUS_PENDING],
                ],
            ]
        ];
    }
}
